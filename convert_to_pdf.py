#!/usr/bin/env python3
import re
from fpdf import FPDF
import os

INPUT_FILE = r'G:\wamp64\www\novaskol-laravel\LIVRE_SOUTENANCE.md'
OUTPUT_PDF = r'G:\wamp64\www\novaskol-laravel\LIVRE_SOUTENANCE.pdf'

class NovaskolPDF(FPDF):
    def __init__(self):
        super().__init__('P', 'mm', 'A4')
        self.set_auto_page_break(True, 20)
        self.in_code = False
        self.code_text = []
        self.in_table = False
        self.table_data = []
        self.chapter_titles = []
        try:
            self.add_font('DejaVu', '', r'C:\Windows\Fonts\DejaVuSans.ttf')
            self.add_font('DejaVu', 'B', r'C:\Windows\Fonts\DejaVuSans-Bold.ttf')
            self.add_font('DejaVu', 'I', r'C:\Windows\Fonts\DejaVuSans.ttf')
            self.add_font('DejaVuMono', '', r'C:\Windows\Fonts\DejaVuSansMono.ttf')
            self.add_font('DejaVuMono', 'B', r'C:\Windows\Fonts\DejaVuSansMono-Bold.ttf')
        except:
            self.add_font('DejaVu', '', r'C:\Windows\Fonts\arial.ttf')
            self.add_font('DejaVu', 'B', r'C:\Windows\Fonts\arialbd.ttf')
            self.add_font('DejaVu', 'I', r'C:\Windows\Fonts\ariali.ttf')
            self.add_font('DejaVuMono', '', r'C:\Windows\Fonts\cour.ttf')
            self.add_font('DejaVuMono', 'B', r'C:\Windows\Fonts\courbd.ttf')

    def header(self):
        if self.page_no() > 2:
            self.set_font('DejaVu', '', 7)
            self.set_text_color(150, 150, 150)
            self.cell(0, 5, 'Livre de Soutenance - Novaskol v1.0.6', 0, 0, 'L')
            self.cell(0, 5, f'Page {self.page_no()}', 0, 1, 'R')
            self.set_draw_color(0, 200, 83)
            self.set_line_width(0.3)
            self.line(10, 12, 200, 12)
            self.ln(5)

    def footer(self):
        self.set_y(-15)
        self.set_font('DejaVu', '', 7)
        self.set_text_color(150, 150, 150)
        self.cell(0, 10, f'Page {self.page_no()}/{{nb}}', 0, 0, 'C')

    def cover_page(self):
        self.add_page()
        self.set_fill_color(8, 14, 24)
        self.rect(0, 0, 210, 297, 'F')
        self.set_y(60)
        self.set_font('DejaVu', 'B', 26)
        self.set_text_color(255, 255, 255)
        self.cell(0, 15, 'LIVRE DE SOUTENANCE', 0, 1, 'C')
        self.ln(5)
        self.set_draw_color(0, 200, 83)
        self.set_line_width(1)
        y = self.get_y()
        self.line(80, y, 130, y)
        self.ln(15)
        self.set_font('DejaVu', 'B', 18)
        self.set_text_color(255, 255, 255)
        self.cell(0, 10, 'Novaskol', 0, 1, 'C')
        self.ln(5)
        self.set_font('DejaVu', '', 11)
        self.set_text_color(180, 190, 210)
        self.cell(0, 7, "Conception et realisation d'une application de gestion", 0, 1, 'C')
        self.cell(0, 7, 'scolaire hors-ligne avec Laravel, Electron et SQLite', 0, 1, 'C')
        self.ln(30)
        self.set_font('DejaVu', '', 11)
        self.set_text_color(200, 200, 220)
        info_lines = [
            ('Presente par :', 'Tojo Nambinina RANDRIAMIFALY'),
            ('Encadre par :', '[Nom de l\'encadrant]'),
        ]
        for label, value in info_lines:
            self.cell(0, 8, f'{label} {value}', 0, 1, 'C')
        self.ln(8)
        self.set_font('DejaVu', '', 10)
        self.set_text_color(160, 170, 200)
        self.cell(0, 7, 'Communication en Audiovisuelle et Numerique (CAN)', 0, 1, 'C')
        self.cell(0, 7, 'Specialisation Developpement Web', 0, 1, 'C')
        self.cell(0, 7, 'Annee universitaire 2025-2026', 0, 1, 'C')

    def write_title(self, text):
        self.ln(3)
        self.set_font('DejaVu', 'B', 20)
        self.set_text_color(8, 14, 24)
        self.multi_cell(0, 12, text, 0, 'C')
        self.ln(5)

    def write_subtitle(self, text):
        self.set_font('DejaVu', '', 13)
        self.set_text_color(80, 80, 80)
        self.multi_cell(0, 8, text, 0, 'C')
        self.ln(5)

    def write_heading1(self, text):
        self.add_page()
        self.set_font('DejaVu', 'B', 16)
        self.set_text_color(8, 14, 24)
        self.set_fill_color(8, 14, 24)
        self.rect(10, self.get_y(), 190, 2, 'F')
        self.ln(6)
        self.multi_cell(0, 10, text, 0, 'L')
        self.set_draw_color(0, 200, 83)
        self.set_line_width(0.5)
        self.line(10, self.get_y(), 200, self.get_y())
        self.ln(4)

    def write_heading2(self, text):
        self.ln(3)
        self.set_font('DejaVu', 'B', 13)
        self.set_text_color(8, 14, 24)
        self.multi_cell(0, 8, text, 0, 'L')
        self.ln(2)

    def write_heading3(self, text):
        self.ln(2)
        self.set_font('DejaVu', 'B', 11)
        self.set_text_color(50, 50, 50)
        self.multi_cell(0, 7, text, 0, 'L')
        self.ln(1)

    def write_heading4(self, text):
        self.ln(1)
        self.set_font('DejaVu', 'I', 10)
        self.set_text_color(60, 60, 60)
        self.multi_cell(0, 6, text, 0, 'L')
        self.ln(1)

    def write_paragraph(self, text):
        text = re.sub(r'\*\*(.+?)\*\*', lambda m: m.group(1), text)
        self.set_font('DejaVu', '', 10)
        self.set_text_color(30, 30, 30)
        self.multi_cell(0, 5.5, text, 0, 'J')
        self.ln(1)

    def write_bold_line(self, text):
        text = text.replace('**', '')
        self.set_font('DejaVu', 'B', 10)
        self.set_text_color(30, 30, 30)
        self.multi_cell(0, 5.5, text, 0, 'L')
        self.ln(1)

    def write_code_block(self, lines):
        self.ln(2)
        line_h = 4
        block_h = len(lines) * line_h + 4
        if self.get_y() + block_h > 270:
            self.add_page()
        self.set_fill_color(245, 245, 250)
        x = self.get_x()
        y = self.get_y()
        self.rect(x + 5, y, 185, block_h, 'F')
        self.set_draw_color(200, 200, 210)
        self.rect(x + 5, y, 185, block_h, 'D')
        self.set_xy(x + 8, y + 2)
        for line in lines:
            self.set_font('DejaVuMono', '', 7)
            self.set_text_color(26, 26, 46)
            self.cell(185, line_h, line[:130], 0, 1)
            self.set_x(x + 8)
        self.ln(3)

    def write_table(self, rows_data):
        if len(rows_data) < 2:
            return
        header = rows_data[0]
        data_rows = []
        for row in rows_data[1:]:
            if all(re.match(r'^[-:\s]+$', c) for c in row if c):
                continue
            data_rows.append(row)
        if not data_rows:
            return
        num_cols = max(len(r) for r in rows_data)
        col_width = 180 / num_cols
        self.ln(2)
        if self.get_y() > 240:
            self.add_page()
        self.set_font('DejaVu', 'B', 8)
        self.set_fill_color(8, 14, 24)
        self.set_text_color(255, 255, 255)
        for j, cell in enumerate(header):
            if j < num_cols:
                self.cell(col_width, 7, cell, 1, 0, 'C', True)
        self.ln()
        self.set_font('DejaVu', '', 8)
        self.set_text_color(30, 30, 30)
        for r_idx, row in enumerate(data_rows):
            if self.get_y() > 265:
                self.add_page()
                self.set_font('DejaVu', 'B', 8)
                self.set_fill_color(8, 14, 24)
                self.set_text_color(255, 255, 255)
                for j, cell in enumerate(header):
                    if j < num_cols:
                        self.cell(col_width, 7, cell, 1, 0, 'C', True)
                self.ln()
                self.set_font('DejaVu', '', 8)
                self.set_text_color(30, 30, 30)
            for j, cell in enumerate(row):
                if j < num_cols:
                    self.cell(col_width, 7, cell[:60], 1, 0, 'L')
            self.ln()
        self.ln(3)

    def write_list_item(self, num, text, ordered=True):
        self.set_font('DejaVu', '', 10)
        self.set_text_color(30, 30, 30)
        prefix = f'  {num}. ' if ordered else '  - '
        self.cell(5, 5, '', 0, 0)
        self.multi_cell(0, 5.5, prefix + text, 0, 'J')
        self.ln(0.5)

    def write_separator(self):
        self.ln(3)
        y = self.get_y()
        if y > 265:
            return
        self.set_draw_color(0, 200, 83)
        self.set_line_width(0.2)
        self.line(10, y, 200, y)
        self.ln(5)

    def write_diagram_line(self, line):
        self.set_font('DejaVuMono', '', 7)
        self.set_text_color(8, 14, 24)
        self.cell(0, 4, line[:130], 0, 1)

def convert():
    pdf = NovaskolPDF()
    pdf.alias_nb_pages()
    pdf.cover_page()

    with open(INPUT_FILE, 'r', encoding='utf-8') as f:
        content = f.read()

    lines = content.split('\n')
    i = 0
    in_code = False
    code_lines = []
    in_table_data = False
    table_rows = []

    while i < len(lines):
        line = lines[i]

        if line.startswith('```'):
            if in_code:
                pdf.write_code_block(code_lines)
                code_lines = []
                in_code = False
            else:
                in_code = True
                code_lines = []
            i += 1
            continue

        if in_code:
            code_lines.append(line)
            i += 1
            continue

        if line.strip().startswith('|') and line.strip().endswith('|'):
            cells = [c.strip() for c in line.strip().split('|')[1:-1]]
            table_rows.append(cells)
            in_table_data = True
            i += 1
            continue

        if in_table_data and (i >= len(lines) or not lines[i].strip().startswith('|')):
            in_table_data = False
            pdf.write_table(table_rows)
            table_rows = []
            continue

        hm = re.match(r'^(#{1,4})\s+(.+)$', line)
        if hm:
            level = len(hm.group(1))
            text = hm.group(2).strip()
            if not text:
                i += 1
                continue
            if level == 1:
                pdf.write_heading1(text)
            elif level == 2:
                pdf.write_heading2(text)
            elif level == 3:
                pdf.write_heading3(text)
            elif level == 4:
                pdf.write_heading4(text)
            i += 1
            continue

        if re.match(r'^-{3,}$', line.strip()):
            pdf.write_separator()
            i += 1
            continue

        if not line.strip():
            i += 1
            continue

        olm = re.match(r'^(\d+)\.\s+(.+)$', line)
        if olm:
            pdf.write_list_item(olm.group(1), olm.group(2), True)
            i += 1
            continue

        uls = re.match(r'^[\-\*]\s+(.+)$', line)
        if uls:
            pdf.write_list_item('', uls.group(1), False)
            i += 1
            continue

        if any(c in line for c in ['┌', '┐', '└', '┘', '│', '─', '├', '┤', '┬', '┴', '┼', '▲', '▼', '◄', '►']):
            simple = line.replace('─', '-').replace('│', '|').replace('┌', '+').replace('┐', '+')
            simple = simple.replace('└', '+').replace('┘', '+').replace('├', '+').replace('┤', '+')
            simple = simple.replace('┬', '+').replace('┴', '+').replace('┼', '+').replace('▲', '^').replace('▼', 'v')
            simple = simple.replace('▶', '>').replace('◀', '<')
            pdf.write_diagram_line(simple)
            i += 1
            continue

        if line.strip().startswith('+') and ('-' in line or '|' in line):
            pdf.write_diagram_line(line)
            i += 1
            continue

        if line.startswith('**') and '** :' in line:
            pdf.write_bold_line(line)
            i += 1
            continue

        stripped = line.strip()
        if stripped.startswith('**') and stripped.endswith('**'):
            pdf.write_bold_line(stripped)
            i += 1
            continue

        if stripped:
            pdf.write_paragraph(stripped)

        i += 1

    pdf.output(OUTPUT_PDF)
    print(f'PDF sauvegarde: {OUTPUT_PDF}')
    print(f'Taille: {os.path.getsize(OUTPUT_PDF) / 1024:.1f} KB')
    print(f'Pages: {pdf.page_no()}')

if __name__ == '__main__':
    convert()
