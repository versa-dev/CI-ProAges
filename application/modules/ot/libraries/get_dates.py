import csv
import time
import sys
import re
import math
import json
from datetime import datetime
from datetime import date
from dateutil import relativedelta

file_path   = str(sys.argv[1])
payment_index = 12

def convert_date(date):
    date_format = datetime.strptime(date, '%Y%m%d')
    new_format = date_format.strftime("%Y-%m-%d")
    return date_format 

def get_start_finish_dates(data):
    array_dates = []

    for row in data:
        payment_date = convert_date(row[payment_index])
        array_dates.append(payment_date)

    array_dates.sort()
    start_date  = array_dates[0]
    return[start_date.month, start_date.year]


with open(file_path,"rb") as csv_file:
	# Get file data
	data = list(csv.reader(csv_file))
	data.pop(0)
	dates = get_start_finish_dates(data)
	print json.dumps(dates)