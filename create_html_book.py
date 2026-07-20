#!/usr/bin/env python3
"""
Convert LIVRE_SOUTENANCE.md to a beautifully styled HTML book
with CSS that renders like an academic publication.
"""

import markdown
import re
import os

INPUT_FILE = r'G:\wamp64\www\novaskol-laravel\LIVRE_SOUTENANCE.md'
OUTPUT_HTML = r'G:\wamp64\www\novaskol-laravel\LIVRE_SOUTENANCE.html'

def convert():
    with open(INPUT_FILE, 'r', encoding='utf-8') as f:
        md = f.read()

    # Pre-process: protect code blocks and ASCII diagrams from markdown processing
    protected_blocks = {}
    counter = [0]

    def protect_code(match):
        counter[0] += 1
        key = f'__CODEBLOCK_{counter[0]}__'
        protected_blocks[key] = match.group(0)
        return key

    def protect_ascii(match):
        counter[0] += 1
        key = f'__ASCIIBLOCK_{counter[0]}__'
        protected_blocks[key] = match.group(0)
        return key

    # Protect ``` blocks
    md = re.sub(r'```[\s\S]*?```', protect_code, md)

    # Protect ASCII diagram blocks (lines with box-drawing chars)
    def has_ascii(line):
        return any(c in line for c in ['┌', '┐', '└', '┘', '│', '─', '├', '┤', '┬', '┴', '┼'])

    lines = md.split('\n')
    new_lines = []
    in_ascii = False
    ascii_block = []
    for line in lines:
        if has_ascii(line) or (in_ascii and line.strip().startswith('+')):
            if not in_ascii:
                in_ascii = True
                ascii_block = [line]
            else:
                ascii_block.append(line)
        else:
            if in_ascii:
                counter[0] += 1
                key = f'__ASCIIBLOCK_{counter[0]}__'
                protected_blocks[key] = '\n'.join(ascii_block)
                new_lines.append(key)
                ascii_block = []
                in_ascii = False
            new_lines.append(line)
    if in_ascii:
        counter[0] += 1
        key = f'__ASCIIBLOCK_{counter[0]}__'
        protected_blocks[key] = '\n'.join(ascii_block)
        new_lines.append(key)
    md = '\n'.join(new_lines)

    # Convert markdown to HTML
    html_body = markdown.markdown(md, extensions=['extra', 'codehilite', 'tables', 'toc'])

    # Restore protected blocks
    for key, value in protected_blocks.items():
        if key.startswith('__CODEBLOCK__'):
            code_content = value.strip('```\n').strip()
            lang_match = re.match(r'^(\w+)\n', code_content)
            lang = lang_match.group(1) if lang_match else ''
            if lang:
                code_content = code_content[len(lang)+1:]
            html_code = f'<pre class="code-block"><code class="language-{lang}">{code_content}</code></pre>'
            html_body = html_body.replace(f'<p>{key}</p>', html_code)
            html_body = html_body.replace(key, html_code)
        elif key.startswith('__ASCIIBLOCK__'):
            html_code = f'<pre class="ascii-diagram">{value}</pre>'
            html_body = html_body.replace(f'<p>{key}</p>', html_code)
            html_body = html_body.replace(key, html_code)

    # CSS styles - professional academic styling
    css = '''
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Source Sans 3', 'Segoe UI', sans-serif;
        color: #1a1a2e;
        background: #fafafa;
        line-height: 1.7;
        font-size: 11pt;
    }

    .book-container {
        max-width: 210mm;
        margin: 0 auto;
        background: white;
        box-shadow: 0 0 30px rgba(0,0,0,0.1);
    }

    /* COVER PAGE */
    .cover-page {
        page-break-after: always;
        height: 297mm;
        background: linear-gradient(135deg, #080e18 0%, #0f1a30 50%, #162240 100%);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 60px 80px;
        position: relative;
        overflow: hidden;
    }
    .cover-page::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 50%, rgba(0,200,83,0.08) 0%, transparent 50%);
    }
    .cover-page .accent-line {
        width: 100px;
        height: 4px;
        background: #00c853;
        margin: 25px auto;
        border-radius: 2px;
    }
    .cover-page h1 {
        font-family: 'Playfair Display', serif;
        font-size: 36pt;
        font-weight: 700;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }
    .cover-page .subtitle {
        font-size: 14pt;
        font-weight: 300;
        color: #aab;
        max-width: 400px;
        line-height: 1.5;
    }
    .cover-page .meta {
        margin-top: 60px;
        font-size: 11pt;
        color: #8899bb;
        line-height: 2;
    }
    .cover-page .meta strong { color: #ccd; }

    /* Front matter */
    .front-matter {
        page-break-after: always;
        padding: 50px 70px;
    }
    .front-matter h2 {
        font-family: 'Playfair Display', serif;
        font-size: 20pt;
        margin-bottom: 20px;
        color: #080e18;
        border-bottom: 2px solid #00c853;
        padding-bottom: 8px;
    }
    .front-matter p {
        margin-bottom: 12px;
        text-align: justify;
    }

    /* Chapter headings */
    h1 {
        font-family: 'Playfair Display', serif;
        font-size: 24pt;
        color: #080e18;
        page-break-before: always;
        page-break-after: avoid;
        padding-top: 40px;
        margin-bottom: 20px;
        border-bottom: 3px solid #00c853;
        padding-bottom: 10px;
    }
    h1:first-of-type { page-break-before: avoid; }

    h2 {
        font-family: 'Playfair Display', serif;
        font-size: 16pt;
        color: #0f1a30;
        margin-top: 30px;
        margin-bottom: 12px;
        page-break-after: avoid;
    }

    h3 {
        font-family: 'Source Sans 3', sans-serif;
        font-size: 13pt;
        font-weight: 600;
        color: #333;
        margin-top: 22px;
        margin-bottom: 8px;
        page-break-after: avoid;
    }

    h4 {
        font-family: 'Source Sans 3', sans-serif;
        font-size: 11pt;
        font-weight: 600;
        font-style: italic;
        color: #555;
        margin-top: 16px;
        margin-bottom: 6px;
    }

    .content {
        padding: 40px 70px 60px;
    }

    p {
        margin-bottom: 10px;
        text-align: justify;
        orphans: 3;
        widows: 3;
    }

    strong { color: #0f1a30; }

    /* Lists */
    ul, ol { margin: 8px 0 12px 30px; }
    li { margin-bottom: 4px; }

    /* Tables */
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
        font-size: 9.5pt;
        page-break-inside: auto;
    }
    thead { display: table-header-group; }
    tr { page-break-inside: avoid; }
    th {
        background: #080e18;
        color: white;
        padding: 8px 10px;
        text-align: left;
        font-weight: 600;
    }
    td {
        padding: 6px 10px;
        border-bottom: 1px solid #ddd;
    }
    tr:nth-child(even) td { background: #f5f5fa; }
    tr:hover td { background: #eef; }

    /* Code blocks */
    pre.code-block {
        background: #1a1a2e;
        color: #e0e0f0;
        padding: 16px 20px;
        border-radius: 8px;
        font-family: 'JetBrains Mono', 'Consolas', monospace;
        font-size: 8pt;
        line-height: 1.6;
        overflow-x: auto;
        margin: 15px 0;
        page-break-inside: avoid;
    }

    pre.ascii-diagram {
        background: #f8f8fc;
        color: #333;
        padding: 12px 16px;
        border-left: 3px solid #00c853;
        font-family: 'JetBrains Mono', 'Consolas', monospace;
        font-size: 8pt;
        line-height: 1.4;
        margin: 12px 0;
        page-break-inside: avoid;
    }

    code {
        font-family: 'JetBrains Mono', 'Consolas', monospace;
        font-size: 9pt;
        background: #f0f0f5;
        padding: 2px 5px;
        border-radius: 3px;
    }

    pre code { background: transparent; padding: 0; }

    /* Blockquotes */
    blockquote {
        border-left: 4px solid #00c853;
        padding: 12px 20px;
        margin: 15px 0;
        background: #f8fff8;
        font-style: italic;
    }

    /* Page breaks */
    .page-break { page-break-before: always; }

    /* Print styles */
    @media print {
        body { background: white; }
        .book-container {
            box-shadow: none;
            max-width: 100%;
        }
        .cover-page { height: 297mm; }
        .content { padding: 30px 50px; }
        .front-matter { padding: 30px 50px; }
        @page { margin: 15mm 20mm; }
    }
    '''

    full_html = f'''<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Livre de Soutenance - Novaskol</title>
<style>{css}</style>
</head>
<body>
<div class="book-container">

<div class="cover-page">
    <div>
        <p style="font-size:11pt;letter-spacing:3px;text-transform:uppercase;color:#8899bb;margin-bottom:20px;">
            Livre de Soutenance
        </p>
        <h1>Novaskol</h1>
        <div class="accent-line"></div>
        <p class="subtitle">
            Conception et realisation d'une application de gestion scolaire<br>
            hors-ligne avec Laravel, Electron et SQLite
        </p>
        <div class="meta">
            <p><strong>Presente par :</strong> Tojo Nambinina RANDRIAMIFALY</p>
            <p><strong>Encadre par :</strong> [Nom de l'encadrant]</p>
            <p style="margin-top:15px;">Communication en Audiovisuelle et Numerique (CAN)</p>
            <p>Specialisation Developpement Web — Année universitaire 2025-2026</p>
        </div>
    </div>
</div>

<div class="content">
{html_body}
</div>

</div>
</body>
</html>'''

    with open(OUTPUT_HTML, 'w', encoding='utf-8') as f:
        f.write(full_html)

    size = os.path.getsize(OUTPUT_HTML)
    print(f'HTML sauvegarde: {OUTPUT_HTML}')
    print(f'Taille: {size/1024:.1f} KB')

if __name__ == '__main__':
    convert()
