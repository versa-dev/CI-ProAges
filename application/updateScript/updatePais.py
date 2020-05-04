#!/usr/bin/python

import datetime
import MySQLdb
import MySQLdb.cursors

def calculatePai(amount, year):

    pai = 0
    if year == 2018 or year == 2017:
        if amount >= 12000 and amount < 110000:
            pai = 1
        elif amount >= 110000 and amount < 500000:
            pai = 2
        elif amount >= 500000:
            pai = 3
    elif year == 2016:
        if amount >= 12000 and amount < 100000:
            pai = 1
        elif amount >= 100000 and amount < 500000:
            pai = 2
        elif amount >= 500000:
            pai = 3
    elif year == 2015:
        if amount >= 10000 and amount < 100000:
            pai = 1
        elif amount >= 100000 and amount < 500000:
            pai = 2
        elif amount >= 500000:
            pai = 3
    else:
        if amount >= 10000:
            pai = 1
    return pai


db = MySQLdb.connect(host="proages-db.coroolzydzjr.us-east-1.rds.amazonaws.com", 
                     user="proages", 
                     passwd="sjme17dkrmtl0km5p", 
                     db="proages-dev", connect_timeout=10,
                     cursorclass=MySQLdb.cursors.DictCursor)
cur = db.cursor()
cursor = db.cursor()
sql = "SELECT * FROM payments WHERE year_prime = 1 and payment_date between '%s-%s-01' and LAST_DAY('%s-%s-01')"
update = "UPDATE payments SET pai_business = %s WHERE pay_tbl_id = %s"
totalAmount = ("SELECT SUM(amount) as total FROM payments WHERE policy_number = %s and year_prime = 1 and "
    "payment_date BETWEEN (SELECT payment_date as fecha FROM payments WHERE policy_number= %s ORDER BY payment_date ASC LIMIT 1) AND %s")

updatePai = dict()
years = [2014, 2015, 2016, 2017,  2018]
months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
totalRows = 0
try:
    cur.execute("UPDATE payments SET pai_business = null")
    for year in years:
        print("Year: %s", year)
        for month in months:
            print ("Month: %s", month)
            values = (year, month, year, month)
            rows = cur.execute(sql, values)
            if  rows > 0:
                for row in cur:
                    totalPai = 0
                    total = 0

                    cursor.execute(
                        "SELECT SUM(pai_business) as totalPai FROM payments WHERE policy_number = %s", row["policy_number"])
                    result_set = cursor.fetchall()
                    for second in result_set:
                        if second['totalPai'] is not None:
                            totalPai = second['totalPai']
                    
                    amountValues = (
                        row["policy_number"], row["policy_number"], row["payment_date"])
                    cursor.execute(totalAmount, amountValues)
                    result_set = cursor.fetchall()
                    for second in result_set:
                        if second['total'] is not None:
                            total = second['total']

                    pai = calculatePai(total, year) - totalPai
                    
                    if pai != 0:
                        valuesUpdate = (pai, row["pay_tbl_id"])
                        cur.execute(update, valuesUpdate)
                        db.commit()
                        totalRows += 1

        print("Rows affected: %s", totalRows)
finally:
    cur.close()
    db.close()


