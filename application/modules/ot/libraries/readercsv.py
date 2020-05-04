# encoding: utf-8
import csv
import time
import sys
import re
from datetime import datetime

file_path   = str(sys.argv[1])

def convert_date(date):
    date_format = datetime.strptime(date, '%Y%m%d')
    new_format = date_format.strftime("%Y-%m-%d")
    return new_format 

def get_name(str_name):
    return str_name.split(' - ',2)[1]

def get_agent_uid(str_name):
    return str_name.split(' - ',2)[0]

def get_policy_uid(str_policy):
    while str_policy[0] == '0':
        str_policy = str_policy[1:]
    return str_policy


with open(file_path,"rb") as csv_file:
    # Get file data
    data = list(csv.reader(csv_file))

    # Extra config before start reading
    data.pop(0)
    lines = ''

    for row in data:
        lines+='<tr>\n'
        lines+='  <td>%s</td>\n' % get_agent_uid(row[1]) # Clave de Agente
        lines+='  <td>%s</td>\n' % get_name(row[1]) # Nombre de Agente
        lines+='  <td>%s</td>\n' % row[0] # Folio
        lines+='  <td>%s</td>\n' % get_policy_uid(row[2]) #Poliza
        lines+='  <td>%s</td>\n' % row[13] #Año Prima
        lines+='  <td>%s</td>\n' % convert_date(row[12]) # Fecha de Pago
        lines+='  <td>%s</td>\n' % row[4] # Prima Neta
        lines+='  <td>%s</td>\n' % row[5] # Prima Ubicar
        lines+='  <td>%s</td>\n' % row[6] # Prima Pago
        lines+='  <td>%s</td>\n' % row[3] # Asegurado
        lines+='  <td>%s</td>\n' % row[7] # Plan
        lines+='</tr>\n'  

    print(lines)

