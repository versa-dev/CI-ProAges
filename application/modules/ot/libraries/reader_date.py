#!/usr/bin/python
import sys
from openpyxl import load_workbook

file_di = str(sys.argv[1])

reader		= load_workbook(filename=file_di, read_only=True)
sheet		= reader["SELO"]

print sheet["H5"].value