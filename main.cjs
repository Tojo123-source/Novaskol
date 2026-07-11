const { app, BrowserWindow, Menu, dialog, ipcMain, shell, nativeImage } = require('electron');
const { spawn } = require('child_process');
const fs = require('fs');
const path = require('path');
const http = require('http');
const { randomBytes } = require('crypto');

const APP_NAME = 'Novaskol';
const APP_URL = 'http://127.0.0.1:8001';
const START_TIMEOUT = 120000;
const WINDOW_BOUNDS = { width: 1440, height: 920, minWidth: 1220, minHeight: 760 };

let splashWindow = null;
let mainWindow = null;
let isQuitting = false;
let isStarting = false;
let phpProcess = null;

app.setName(APP_NAME);
app.setAppUserModelId('com.novaskol.desktop');
if (!app.requestSingleInstanceLock()) { app.quit(); }

app.on('second-instance', () => {
  const target = mainWindow && !mainWindow.isDestroyed() ? mainWindow : splashWindow;
  if (target) { target.isMinimized() ? target.restore() : target.focus(); }
});

function getRuntimeRoot() {
  if (app.isPackaged) return path.join(process.resourcesPath, 'seed', 'novaskol');
  const dr = path.join(path.resolve(__dirname, '..'), 'storage', 'app', 'distribution', 'novaskol-app-latest');
  if (process.env.NOVASKOL_DESKTOP_SOURCE === 'distribution' && fs.existsSync(dr)) return dr;
  return path.resolve(__dirname, '..');
}

function ensureDir(p) { fs.mkdirSync(p, { recursive: true }); }
function log(r, m) {
  try {
    const lp = path.join(r, 'storage', 'logs', 'novaskol-desktop.log');
    ensureDir(path.dirname(lp));
    fs.appendFileSync(lp, `[${new Date().toISOString().slice(0,19).replace('T',' ')}] ${m}\n`, 'utf8');
  } catch {}
}
function getPhp(r) {
  const candidates = [
    path.join(r, 'tools', 'runtime', 'php', 'php.exe'),
    path.join(r, 'runtime', 'php', 'php.exe'),
    path.join(process.resourcesPath, 'seed', 'novaskol', 'tools', 'runtime', 'php', 'php.exe'),
    path.join(__dirname, '..', '..', 'seed', 'novaskol', 'tools', 'runtime', 'php', 'php.exe'),
  ];
  for (const x of candidates) {
    if (fs.existsSync(x)) return x;
  }
  const msg = `PHP introuvable. Chemins vérifiés :\n${candidates.join('\n')}\n\nLe dossier resources/seed/novaskol/tools/runtime/php/ doit contenir php.exe.`;
  log(r, msg);
  throw new Error(msg);
}
function ensureLayout(r) {
  for (const d of ['storage/app','storage/app/public','storage/framework','storage/framework/cache/data','storage/framework/sessions','storage/framework/views','storage/logs','bootstrap/cache'])
    ensureDir(path.join(r, d));
}
function getIcon() {
  const c = app.isPackaged ? path.join(process.resourcesPath, 'assets', 'novaskol.ico') : path.join(__dirname, 'assets', 'novaskol.ico');
  return fs.existsSync(c) ? c : undefined;
}
function getIconPath() { return getIcon() || (app.isPackaged ? path.join(process.resourcesPath, 'assets', 'novaskol.png') : path.join(__dirname, 'assets', 'novaskol.png')); }
function sendSplash(m) { try { splashWindow?.webContents.send('splash-status', m); } catch {} }

function createSplash() {
  splashWindow = new BrowserWindow({
    width: 460, height: 310, frame: false, resizable: false, movable: true,
    fullscreenable: false, minimizable: false, maximizable: false, show: false,
    backgroundColor: '#080e18', icon: getIconPath(),
    webPreferences: { preload: path.join(__dirname, 'preload.cjs'), contextIsolation: true, sandbox: false },
  });
  splashWindow.removeMenu();
  splashWindow.loadFile(path.join(__dirname, 'ui', 'splash.html'), { query: { version: app.getVersion(), product: APP_NAME } });
  splashWindow.once('ready-to-show', () => splashWindow.show());
  if (getIcon()) splashWindow.setIcon(nativeImage.createFromPath(getIcon()));
}

function createMain() {
  mainWindow = new BrowserWindow({
    ...WINDOW_BOUNDS, show: false, backgroundColor: '#080e18', title: APP_NAME, icon: getIconPath(),
    autoHideMenuBar: true,
    webPreferences: { preload: path.join(__dirname, 'preload.cjs'), contextIsolation: true, sandbox: false, spellcheck: false, backgroundThrottling: false },
  });

  mainWindow.once('ready-to-show', () => { splashWindow?.close(); mainWindow.show(); });
  mainWindow.on('close', async (e) => {
    if (isQuitting) return;
    e.preventDefault();
    const c = await dialog.showMessageBox(mainWindow, { type:'question', buttons:['Quitter Novaskol','Annuler'], defaultId:1, cancelId:1, title:'Novaskol', message:'Voulez-vous vraiment quitter Novaskol ?' });
    if (c.response !== 0) return;
    isQuitting = true;
    await stopNovaskol();
    app.quit();
  });
  mainWindow.on('closed', () => mainWindow = null);
  if (getIcon()) mainWindow.setIcon(nativeImage.createFromPath(getIcon()));
  mainWindow.webContents.setWindowOpenHandler(({url}) => { shell.openExternal(url); return {action:'deny'}; });
}

function waitForHttp(url, timeout = START_TIMEOUT) {
  return new Promise((res, rej) => {
    const deadline = Date.now() + timeout;
    let interval = 100;
    const go = () => {
      const r = http.get(url, (resp) => { resp.resume(); res(); });
      r.on('error', () => { if (Date.now() >= deadline) { rej(new Error(`Novaskol ne répond pas sur ${url}.`)); return; } interval = Math.min(interval * 1.5, 1000); setTimeout(go, interval); });
      r.setTimeout(2000, () => r.destroy());
    };
    go();
  });
}

async function startNovaskol(r) {
  if (isStarting) return;
  isStarting = true;
  ensureLayout(r);

  const env = path.join(r, '.env');
  if (!fs.existsSync(env)) {
    const ex = path.join(r, '.env.local.example');
    if (fs.existsSync(ex)) fs.copyFileSync(ex, env);
  }
  if (fs.existsSync(env)) {
    let c = fs.readFileSync(env, 'utf8');
    const set = (k, v) => { const re = new RegExp(`^${k}=.*$`, 'm'); const l = `${k}=${v}`; if (re.test(c)) c = c.replace(re, l); else c += `\n${l}\n`; };
    set('APP_URL', APP_URL); set('DB_CONNECTION', 'sqlite');
    const db = path.join(r, 'storage/novaskol.sqlite').replace(/\\/g, '/');
    set('APP_ENV', 'production');
    set('APP_DEBUG', 'true');
    set('LOG_LEVEL', 'debug');
    set('DB_DATABASE', db);
    if (c.match(/^APP_KEY=$/m)) {
      sendSplash('Génération de la cle...');
      c = c.replace(/^APP_KEY=$/m, 'APP_KEY=base64:' + randomBytes(32).toString('base64'));
    }
    fs.writeFileSync(env, c, 'utf8');
    if (!fs.existsSync(db)) fs.writeFileSync(db, '');
  }

  if (!fs.existsSync(path.join(r, 'vendor', 'autoload.php'))) {
    sendSplash('Installation des dépendances...');
    const composer = path.join(r, 'tools', 'runtime', 'composer.phar');
    if (fs.existsSync(composer)) {
      const cp = spawn(getPhp(r), [composer, 'install', '--no-dev', '--no-interaction', '--prefer-dist'], { cwd: r, windowsHide: true, stdio: 'ignore' });
      await new Promise(r2 => cp.on('close', r2));
      sendSplash('Mise à jour de la base...');
      const mp = spawn(getPhp(r), [path.join(r, 'artisan'), 'migrate', '--force'], { cwd: r, windowsHide: true, stdio: 'ignore' });
      await new Promise(r2 => mp.on('close', r2));
    } else {
      log(r, 'composer.phar introuvable');
    }
  }

  sendSplash('Démarrage du moteur...');
  phpProcess = spawn(getPhp(r), [path.join(r, 'artisan'), 'serve', '--host=127.0.0.1', '--port=8001'], { cwd: r, windowsHide: true, stdio: ['ignore', 'pipe', 'pipe'] });
  fs.writeFileSync(path.join(r, 'storage', 'app', 'novaskol-server.pid'), String(phpProcess.pid));
  phpProcess.stderr.on('data', (c) => log(r, `[PHP] ${c}`));
  phpProcess.on('error', (e) => log(r, `[PHP] Error: ${e.message}`));
  phpProcess.on('close', (c) => { log(r, `[PHP] exited ${c}`); phpProcess = null; });

  sendSplash('Connexion...');
  await waitForHttp(APP_URL);
  log(r, 'Novaskol prêt.');
}

async function stopNovaskol() {
  if (phpProcess) { try { phpProcess.kill('SIGTERM'); } catch {} phpProcess = null; }
  const root = getRuntimeRoot();
  const stop = path.join(root, 'tools', 'windows', 'Stop-Novaskol.ps1');
  if (fs.existsSync(stop)) {
    try {
      const s = spawn('powershell.exe', ['-NoProfile', '-File', stop, '-Silent'], { windowsHide: true, stdio: 'ignore' });
      await new Promise(r => s.on('exit', r));
    } catch {}
  }
}

async function openDesktopApp() {
  const r = getRuntimeRoot();
  Menu.setApplicationMenu(null);
  if (!splashWindow || splashWindow.isDestroyed()) createSplash();
  try {
    await startNovaskol(r);
    if (!mainWindow || mainWindow.isDestroyed()) createMain();
    mainWindow.loadURL(APP_URL);
  } catch (err) {
    log(r, `Echec: ${err.message}`);
    const c = await dialog.showMessageBox({ type:'error', buttons:['Ouvrir logs','Réessayer','Fermer'], defaultId:1, cancelId:2, title:'Novaskol', message:"Novaskol n'a pas pu s'ouvrir.", detail:err.message });
    if (c.response === 0) shell.openPath(path.join(r, 'storage', 'logs'));
    else if (c.response === 1) { isStarting = false; await openDesktopApp(); return; }
    app.quit();
  } finally { isStarting = false; }
}

ipcMain.handle('desktop:get-meta', () => ({ version: app.getVersion(), productName: APP_NAME }));
app.whenReady().then(openDesktopApp);
app.on('activate', async () => { if (!BrowserWindow.getAllWindows().length) await openDesktopApp(); });
app.on('before-quit', async (e) => { if (isQuitting) return; e.preventDefault(); isQuitting = true; await stopNovaskol(); app.quit(); });
