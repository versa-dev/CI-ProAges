#!/usr/bin/env python
# -*- coding: 850 -*-
# -*- coding: utf-8 -*-
import csv
import time
import sys
import re
import math
import mysql.connector
import datetime
import json
from mysql.connector import Error
from mysql.connector import errorcode
from datetime import datetime
from datetime import date
from dateutil import relativedelta

start_time = time.time()

is_vida     = True
is_nuevo    = 1
today       = datetime.now()
date_today  = today.strftime("%Y-%m-%d %H:%M:%S")
dates       = []
file_path   = str(sys.argv[1])
db_name     = str(sys.argv[2])
year_values= {'2015':{'year':2015,'minimuns':[10000,100000,500000],'pai_values':[1,2,3]},
           '2016':{'year':2016,'minimuns':[10000,100000,500000],'pai_values':[1,2,3]},
           '2017':{'year':2017,'minimuns':[10000,110000,500000],'pai_values':[1,2,3]},
           '2018':{'year':2017,'minimuns':[10000,110000,500000],'pai_values':[1,2,3]},
           '2019':{'year':2017,'minimuns':[14000,120000,500000],'pai_values':[1,2,3]},
           '2020':{'year':2017,'minimuns':[12000,50000,140000],'pai_values':[1,1.5,2]}}

#Define variables of index for common values
bussiness             = 0
product_id            = 1
folio_index           = 0
agent_uid_index       = 1
policy_index          = 2
insured_name_index    = 3
amount_index          = 4
allocated_prime_index = 5
bonus_prime_index     = 6
plan_index            = 7
ramo_index            = 11
payment_index         = 12
year_pr_index         = 13



status = "success"

def get_agent_name(str_name):
    return str_name.split(' - ',2)[1]

def get_agent_uid(str_name):
    uid = str_name.split(' - ',2)[0]
    return uid

def get_policy_uid(str_policy):
    while str_policy[0] == '0':
        str_policy = str_policy[1:]
    return str_policy

def convert_date(date):
    date_format = datetime.strptime(date, '%Y%m%d')
    new_format = date_format.strftime("%Y-%m-%d")
    return new_format 

def get_anio_prima(ano_prima):
    if ano_prima == 'NUEVO':
        return 1
    else:
        return 2

def get_start_finish_dates(data, is_vida):
    array_dates = []

    for row in data:
        payment_date = convert_date(row[payment_index])
        array_dates.append(payment_date)

    array_dates.sort()
    start_date  = array_dates[0]
    finish_date = array_dates[len(array_dates)-1]
    return[start_date, finish_date]

def delete_selo(product_id,start_date, finish_date, is_nuevo):
    query = "DELETE FROM payments WHERE payment_date >= '"+str(start_date)+"' AND payment_date <= '"+str(finish_date)+"' AND product_group = "+str(product_id)+" AND year_prime='"+str(is_nuevo)+"';"
    cursor.execute(query)

def get_imported_folio(row):
    if is_vida:
        return row[0]
    else:
        return get_policy_uid(row[policy_index])

def get_allocated_prime(row):
    if is_vida:
        return float(row[5])
    else:
        return row[5]

def get_bonus_prime(row):
    if is_vida:
        return float(row[6])
    else:
        return row[6]

def get_agent_id(agent_uid, type_agent, name_agent):
    query = "SELECT `agent_id` FROM (`agent_uids`) WHERE `agent_uids`.`type` = '"+str(type_agent)+"' AND `agent_uids`.`uid` LIKE '%"+str(agent_uid)+"%' ORDER BY `id` asc LIMIT 1"
    cursor.execute(query)
    record = cursor.fetchone()
    if record is None:
        return get_agent_id_by_name(name_agent)
    else:
        return record[0]

def get_agent_id_by_name(name):
    query = "SELECT agents.id FROM users JOIN agents ON agents.user_id = users.id WHERE concat(' ',lastnames,' ',name)='"+str(name)+"';"
    cursor.execute(query)
    record = cursor.fetchone()
    if record is None:
        return 0
    else:
        return record[0]

def update_agent_id(agents_arrays):
    query = "UPDATE payments SET agent_id = %s WHERE agent_clave = %s"
    cursor.executemany(query,agents_arrays)

def set_agent_ids(start_date, finish_date, is_vida):
    type_agent = "provincial"
    if is_vida:
        type_agent = "national"
    query = "SELECT DISTINCT agent_clave, imported_agent_name FROM payments WHERE agent_id = 0 AND payment_date >= '"+str(start_date)+"' AND payment_date <= '"+str(finish_date)+"'"
    cursor.execute(query)
    array_result = cursor.fetchall()
    agents_array = []
    for row in array_result:
        agent_id = get_agent_id(u""+row[0],type_agent, u""+row[1])
        agents_array.append([str(agent_id),row[0].encode("ascii")])
    update_agent_id(agents_array)

def get_policies_amount(start_date, finish_date, minimun):
    query = "SELECT SUM(amount), policy_number FROM payments WHERE payment_date >= '"+str(start_date)+"' AND payment_date <= '"+str(finish_date)+"' AND product_group = 1 AND year_prime=1 GROUP BY policy_number HAVING SUM(amount)>="+str(minimun)+" order by SUM(amount) desc;"
    cursor.execute(query)
    array_result = cursor.fetchall()
    return array_result

def compare_pai(amount, value1, value2, value3, pai_values):
    if amount >= value1 and amount < value2:
        return pai_values[0]
    elif amount >= value2 and amount < value3:
        return pai_values[1]
    elif amount >= value3:
        return pai_values[2]
    return 0   

def get_payments(policy_uid, start_date, finish_date):
    query = "SELECT pay_tbl_id,amount,payment_date FROM payments WHERE year_prime=1 AND product_group=1 AND policy_number='"+str(policy_uid)+"' AND payment_date >= '"+str(start_date)+"' AND payment_date <= '"+str(finish_date)+"' ORDER BY payment_date ASC"
    cursor.execute(query)
    array_result = cursor.fetchall()
    return array_result

def update_pai(array_payments):
    query = "UPDATE payments SET pai_business =%s WHERE pay_tbl_id=%s"
    cursor.executemany(query,array_payments)

def get_minimun_pai(pai,year):
    if (pai==year_values[str(year)]['pai_values'][0]):
        return year_values[str(year)]['minimuns'][0]
    if (pai==year_values[str(year)]['pai_values'][1]):
        return year_values[str(year)]['minimuns'][1]
    if (pai==year_values[str(year)]['pai_values'][2]):
        return year_values[str(year)]['minimuns'][2]


def set_negocios_pai(start_date, finish_date):
    year           = datetime.strptime(start_date, '%Y-%m-%d').year
    minimuns       = year_values[str(year)]['minimuns']
    array_policies = get_policies_amount(start_date,finish_date,minimuns[0])
    array_payments = []
    pai_of_total = 0
    for row in array_policies:
        flag = True
        pai  = compare_pai(row[0], minimuns[0], minimuns[1], minimuns[2], year_values[str(year)]['pai_values'])
        payments  = get_payments(row[1],start_date,finish_date)
        pai_total = get_minimun_pai(pai,year)
        total     = 0
        single_payment = 0
        for payment in payments:
            total = total + payment[1]
            single_payment = payment
        if total >= pai_total:
            pai_of_total = pai_of_total + pai
            array_payments.append([pai,single_payment[0]])

    update_pai(array_payments)

def get_dates_by_agent(agent_id, start_date, finish_date):
    query = "SELECT DISTINCT payment_date FROM payments WHERE payment_date >= '"+str(start_date)+"' AND payment_date <= '"+str(finish_date)+"' AND product_group = "+str(product_id)+" AND agent_id = "+str(agent_id)+" ORDER BY payment_date ASC;"
    cursor.execute(query)
    array_result = cursor.fetchall()
    return array_result

def get_generation(agent_id, connection_date ,payment_date, is_vida):
    period = 4
    generation = 0
    str_generation = ""
    if is_vida:
        period = 3
    r= relativedelta.relativedelta(payment_date, connection_date)
    quarter_pay = ""+str(payment_date.year)+""+str(math.ceil(payment_date.month/period)*1)
    quarter_con = ""+str(connection_date.year)+""+str(math.ceil(payment_date.month/period)*1)
    if r.years < 1:
        generation = 1
    else:
        if quarter_pay > quarter_con:
            generation = r.years + 1
        else:
            generation = r.years

    if generation >= 5:
        str_generation = "Consolidado"
    else:
        str_generation = "Generaci\xf3n "+str(generation)
        str_generation = str_generation.decode("unicode_escape")

    return str_generation
    
def update_generations(array_generations):
    query = "UPDATE payments SET agent_generation =%s WHERE agent_id=%s AND payment_date=%s"
    cursor.executemany(query,array_generations)

def set_agents_generations(start_date,finish_date, is_vida):
    query = "SELECT DISTINCT agent_id, connection_date FROM payments JOIN agents ON payments.agent_id = agents.user_id WHERE payment_date >= '"+str(start_date)+"' AND payment_date <= '"+str(finish_date)+"' AND product_group = '"+str(product_id)+"';"
    cursor.execute(query)
    array_result = cursor.fetchall()
    array_generations = []

    for agent in array_result:
        array_dates = get_dates_by_agent(agent[0],start_date,finish_date)
        for row in array_dates:
            generation = ""
            if agent[1] is None:
                generation = "Consolidado"
            else:
                generation = get_generation(agent[0], agent[1] ,row[0], is_vida)
            array_generations.append([generation,agent[0],row[0].strftime("%Y-%m-%d")])
    update_generations(array_generations)

def save_data_with_breaks(file, index, is_vida, query):
    data_rows = []
    for x in range(0,10000):
        data = set_data_row(file[index], is_vida)
        data_rows.append(data)
        index = index + 1;
        if index >= len(file):
            break
    if index < len(file):
        cursor.executemany(query,data_rows)
        connection.commit()
        save_data_with_breaks(file, index, is_vida, query)
    else:
        cursor.executemany(query,data_rows)
        connection.commit()


def set_data_row(row, is_vida):
    data_row = []
    # Global data that appears on both products
    data_row.append(0) #negocio_pai
    data_row.append(0) #agent_id
    data_row.append(product_id) #product_group
    data_row.append(get_anio_prima(row[year_pr_index])) #year_prime
    data_row.append(1) #currency_id
    data_row.append(float(row[amount_index])) #amount
    data_row.append(convert_date(row[payment_index])) #payment_date
    data_row.append(bussiness) #business
    data_row.append(get_policy_uid(row[policy_index])) #policy_number
    data_row.append(date_today) #last_updated
    data_row.append(date_today) #date
    data_row.append(convert_date(row[payment_index])) #import_date
    data_row.append(get_agent_name(row[agent_uid_index])) #imported_agent_name
    data_row.append(row[folio_index]) #imported_folio
    data_row.append(get_allocated_prime(row)) #allocated_prime
    data_row.append(get_bonus_prime(row)) #bonus_prime
    data_row.append(get_agent_uid(row[agent_uid_index])) #agent_clave
    data_row.append(row[insured_name_index][:45]) #insured_name
    data_row.append(row[plan_index]) #plan_type
    return data_row

def get_missing_agents():
    query = "SELECT pay_tbl_id, agent_clave, imported_agent_name, policy_number, year_prime, amount, allocated_prime, bonus_prime, payment_date FROM payments WHERE agent_id=0 ORDER BY agent_clave DESC"
    cursor.execute(query)
    array_result = cursor.fetchall()
    array_policies = []
    if(len(array_result) > 0):
        for policy in array_result:
            array_policies.append(policy);
    return array_policies

def get_options_agents():
    str_options = ''
    query = "SELECT agents.id, IF (CONCAT(name,' ',lastnames)<>' ', CONCAT(name,' ',lastnames), company_name)as name FROM agents JOIN users on users.id = agents.user_id order by name ASC"
    cursor.execute(query)
    array_result = cursor.fetchall()
    if(len(array_result) > 0):
        str_options = str_options + '<option value="0"></option>'
        for agent in array_result:
            str_options = str_options + '<option value="%s">%s</option>' % (agent[0] , agent[1])
        return str_options


def get_html_table(missing_agents):
    options = get_options_agents()
    table_str = '';
    table_str += '<table id="table_missings">'
    table_str += '   <thead>'
    table_str += '       <tr>'
    table_str += '           <th>Feha de pago</th>'
    table_str += '           <th>Clave del agente</th>'
    table_str += '           <th>Nombre del agente en el archivo</th>'
    table_str += '           <th>Nombre del agente a asignar</th>'
    table_str += '           <th>Poliza</th>'
    table_str += '           <th>Anio prima</th>'
    table_str += '           <th>Monto</th>'
    table_str += '           <th>Prima para ubicar</th>'
    table_str += '           <th>Prima para pago de bono</th>'
    table_str += '       </th>'
    table_str += '   </thead>'
    table_str += '   <tbody>'
    for payment in missing_agents:
        table_str += '       <tr>'
        table_str += '           <td>'+str(payment[8])+'</td>'
        table_str += '           <td>'+str(payment[1])+'</td>'
        table_str += '           <td>%s</td>' % payment[2]
        table_str += '           <td><select id="select_agent" onChange="update_payment('+str(payment[0])+',this)">'+options+'</select></td>'
        table_str += '           <td>'+str(payment[3])+'</td>'
        table_str += '           <td>'+str(payment[4])+'</td>'
        table_str += '           <td>'+str(payment[5])+'</td>'
        table_str += '           <td>'+str(payment[6])+'</td>'
        table_str += '           <td>'+str(payment[7])+'</td>'
        table_str += '       </tr>'
    table_str += '   </tbody>'
    table_str += '</table>'
    return table_str       


try:
    query = ""
    #Set config of db connection_date
    connection  = mysql.connector.connect(host='proages-db.coroolzydzjr.us-east-1.rds.amazonaws.com', database= db_name, user='proages', password='sjme17dkrmtl0km5p')
    cursor = connection.cursor(buffered=True)
    cursor.execute("SET autocommit = 0")

    # Reader of the file
    with open(file_path,"rb") as csv_file:
        # Get file data
        data = list(csv.reader(csv_file))

        # Determine if is Vida or GMM
        if data[1][ramo_index] == "SALUD":
            is_vida = False
            product_id = 2

        # Determine if is APRI = 1
        if data[1][year_pr_index] == "RENOVADO":
            is_nuevo = 2

        # Set insert string 
        query = """INSERT INTO payments (pai_business,agent_id, product_group, year_prime, currency_id, amount, payment_date, business, policy_number, last_updated, date, import_date, imported_agent_name, imported_folio, allocated_prime, bonus_prime, agent_clave, insured_name, plan_type ) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )""" 

        # Extra config before start reading
        data.pop(0)
        data_rows = []
        today = datetime.now()
        date_today = today.strftime("%Y-%m-%d %H:%M:%S")
        dates = get_start_finish_dates(data, is_vida)

        # Process of import csv
        delete_selo(product_id,dates[0],dates[1],is_nuevo)
        save_data_with_breaks(data,0,is_vida, query)

        set_agent_ids(dates[0],dates[1], is_vida)
        connection.commit()

        set_agents_generations(dates[0],dates[1],is_vida)
        connection.commit()

        if is_vida:
            set_negocios_pai(dates[0],dates[1])
            connection.commit()

        missing_agents = get_missing_agents()

        if(len(missing_agents)>0):
            table = get_html_table(missing_agents)
            status = table
            status = '%s' % table

        cursor.close()
        connection.close()

except mysql.connector.Error as error :
    status = error
    print(status)
finally:
    # print("--- %s seconds ---" % (time.time() - start_time))
    print(status.encode('utf-8', errors='ignore'))