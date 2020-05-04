#!/usr/bin/env python

import pandas as pd
import numpy
import time
# Your directory
#mydir = (os.getcwd()).replace('\\','/') + '/'

df=pd.read_excel(r'test_prod_sa.xlsx')
df = df.replace(numpy.NaN, "")
df['ASEGURADO']=df['ASEGURADO'].str.replace(u"\ufffd","?")
df['ASEGURADO']=df['ASEGURADO'].str.replace(u"\u2550","?")
df['ASEGURADO']=df['ASEGURADO'].str.replace(u"\u2551","?")
df['ASEGURADO']=df['ASEGURADO'].str.replace(u"\u2554","?")
df['ASEGURADO']=df['ASEGURADO'].str.replace(u"\u2534","?")

df['PLAN']=df['PLAN'].str.replace(u"\ufffd","?")
df['PLAN']=df['PLAN'].str.replace(u"\u2550","?")
df['PLAN']=df['PLAN'].str.replace(u"\u2551","?")
df['PLAN']=df['PLAN'].str.replace(u"\u2554","?")
df['PLAN']=df['PLAN'].str.replace(u"\u2534","?")
#print(df)

# Establish a MySQL connection
# database = MySQLdb.connect (host="127.0.0.1", user = "root", passwd = "LAZARO9318", db = "test", use_unicode=True, charset="utf8")
# print(database)
# Get the cursor, which is used to traverse the database, line by line
# cursor = database.cursor()

# Create the INSERT INTO sql query
# query = """INSERT INTO production_gmm_ (FECSIS, CLAVE, POLIZA_, ST_NUEREN, PMANETA, PMAUBI, PRIMAAFE, ASEGURADO, PLAN, PERIODO, TIPO_DEDUCIBLE) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""

for i in range(len(df)):
    FECSIS=df['FECSIS'][i]
    CLAVE=df['CLAVE'][i]
    POLIZA_=df['POLIZA_'][i]
    ST_NUEREN=df['ST_NUEREN'][i]
    PMANETA=df['PMANETA'][i]
    PMAUBI=df['PMAUBI'][i]
    PRIMAAFE=df['PRIMAAFE'][i]
    ASEGURADO=df['ASEGURADO'][i]
    PLAN=df['PLAN'][i]
    PERIODO=df['PERIODO'][i]
    TIPO_DEDUCIBLE=df['TIPO_DEDUCIBLE'][i]

    # Assign values from each row
    values=(FECSIS, CLAVE,POLIZA_,ST_NUEREN,PMANETA,PMAUBI,PRIMAAFE,ASEGURADO,PLAN,PERIODO,TIPO_DEDUCIBLE)
    # print(values)
    # Execute sql Query
    # cursor.execute(query, values)

# Close the cursor
# cursor.close()

# Commit the transaction
# database.commit()

# Close the database connection
# database.close()
print('Insert success!')