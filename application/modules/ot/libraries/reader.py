#!/usr/bin/python
# -*- coding: utf-8 -*-

import time
import sys
import re
from openpyxl import load_workbook
import mysql.connector
from mysql.connector import Error
from mysql.connector import errorcode

def get_list_indexes(array_keys,array_headers):
	array_indexes = []
	for key in array_keys:
		array_indexes.append(array_headers.index(key))
	return array_indexes

def get_array_keys_by_sheet(name_sheet):
	if sh_name == 'produccion_':
		return ["INTERL","INTER","FLA_NUM","POLIZA","APRI","FEMOVIBO","FEFIPEBO","IMPRI","PO_UBI1_DA","PO_PAG1_DA","DNNOM","PLAN","FECONTBO","INCREMEN","PERIODO"]

	if sh_name == 'produccion_sa':
   		return ["FECSIS", "CLAVE", "POLIZA_", "ST_NUEREN", "PMANETA", "PMAUBI", "PRIMAAFE", "ASEGURADO", "PLAN", "PERIODO", "TIPO_DEDUCIBLE"]

	if sh_name == 'conservacion_':
		return ["INTERL","INTER","TIPOHO","POLIZA1", "NOMBRE","PLAN","FEC_EMIS","PAG_HASTA","AIO_POL" ,"PRIMA1","CONS_REAL1","CONS_ACOT1","PRISFP1", "MONEDA1", "FOR_PAGO1","PE_CON_ZONA", "PERIODO"]

	if sh_name == 'bona':
		return ["PERIODO", "INTER","NOMBRE","ZONA","ST","TIPO_GTE","FEC_CNSF","GENERACION" , "PAFECT1", "PAFECT2", "INCRE","PAFECT1_EXC", "PAFECT2_EXC", "PROD_BONO_1_UBI", "PROD_BONO_1_PAG", "PROD_BON_2_UBI","PROD_BON_2_PAG","NEGS_BONO","NEGS_BONO_PAG","BASEA","PconReal","PconAcot","BASE_CA","NPOL_CA", "BASE_CP", "NPOL_CP", "BASE_PP", "NPOL_PP", "BASE_CO", "NPOL_CO", "BASE_RE", "NPOL_RE", "COL1","RENGLON1","PORBONO1","BONO1","COL2","RENGLON2","PORBONO2","BONO2","RENGLON3","PORBONO3","BONO3","PROD_TOT","PTOS_TOT","TABLA","TIPO_","MIN_CONS","BASE_MIN_CERO","BON_CRE", "BXACOT","PRO_INC_ANT", "PRO_INC_ACT", "POR_INCREMENTO","PROD_NOV","PROD_CT", "CONS_MIN","MES_CORTE","FEC_ACT", "CONGRESO","FEC_CORRIDA", "PUNTOS_DESARROLLO"]

	if sh_name == 'negocios_':
		return ["INTERL","INTER","ASEGURADO","PLAN","PMA_PAGADA","PMA_ANUAL","neg_camp_mas_pai","NEG_PAGA","PERIODO"]

	if sh_name == 'puntos_':
		return ["PERIODO","ZONA","INTER","NOMBRE","GENERACION","RENGLON1","PROD_BONO_1_PAG","PTOS_TOT"]

	if sh_name == 'bona_vi':
		return ["PERIODO", "INTER","FOLIO","NOMBRE","ZONA","ST","TIPO_GTE","FEC_CONEC","GENERACION","PAFECT1", "PAFECT2", "INCRE","PAFECT1_EXC" ,"PAFECT2_EXC", "PROD_BONO_1_UBI", "PROD_BONO_1_PAG", "PROD_BON_2_UBI","PROD_BON_2_PAG","NEGS_BONO","BASEA","PconReal","PconAcot","NPOL_CA" ,"BASE_CA", "NPOL_CP", "BASE_CP", "NPOL_PP", "BASE_PP", "NPOL_CO", "BASE_CO", "NPOL_RE", "BASE_RE", "COL1","RENGLON1","PORBONO1","BONO1","COL2","RENGLON2","PORBONO2","BONO2","BONO_TOT","PTOS_TOT","PTOS_INT_VI","TABLA","TIPO_","MIN_CONS","BASE_MIN_CERO","BON_CRE","BXACOT","CLUB_ELITE","BONO1_SA","PTOS_SA" ,"BONO1_AU","PTOS_AU", "BONO_TOT_INT","PTOS_TOT_INT","PORBONO_INT" ,"BONO_INT","BONO_CONSTANCIA_NEGS","CONGRESO","FEC_ACT", "TIPO_AGRUP","FEC_CORRIDA" ,"PUNTOS_DESARROLLO"]

	if sh_name == 'bong_sa':
		return ["PERIODO","FOLIO","ZONA","NOMBRE","FEC_CONEC","GENERACION","ST","INTER","PMA_AFE1","PMA_AFE2","PMA_UBI1","PMA_UBI2","PMA_PAG1","PMA_PAG2","PMA_EXC1","PMA_EXC2","PMA_ANUAL","PMA_ULT_12","SINI_REAL","SINACO","PORSINI_REAL","PORSINI" ,"INDICADOR_PORSINI","RENGLON1","COLUMNA1","PORBONO1","BONO1","RENGLON2","COLUMNA2","PORBONO2","BONO2","BONO_TOTAL","PTOS_PROD","PORBONO_DESARROLLO","BONO_DESARROLLO","NUM_ASEG","TABLA","PMA_NOV","CONGRESO","FEC_ACT","CONS_VIDA","PMA_INI_VIDA","PMA_NOV_VIDA","FECHA_CORRIDA"]

	if sh_name == 'siniestralidad_':
		return ["FECHA","AGENTE","ASEGURADO","NUMRECL", "POLIZA","TOTAL","SINACO_DA","PERIODO"]

	if sh_name == 'asegurados_':
		return ["PERIODO","LIDER","F_LIDER","ASEGURADO","NUMCERT","FEC_ANTIG","POLIZA"]

	if sh_name == 'BONA_SA':
		return ["PERIODO","LIDER","NLIDER","FECCONEC","GENERACION","ZONA_PROV","TIPO_GTE","SUBGRUPO","ST_PRO","PMA_AFE1","PMA_UBI1","PMA_PAG1","PMA_EXC1","PMA_AFE2","PMA_UBI2","PMA_PAG2","PMA_EXC2","PMA_ANUAL","N_ASEG","SINI_REAL","SINACO_AGT","PMA_ULT_12","PORSINI_REAL","PORSINI","INDICADOR_PORSINI","RENGLON1","COLUMNA1","PORBONO1","BONO1","PTOS_PROD_DA","RENGLON2","COLUMNA2","PORBONO2","BONO2","PTOS","BONO_TOT","AGT_PROD","IND_PAGO","TABLA","RENGLON_VI","PMA_VI","PTOS_VI","BONO_VI","RENGLON_AU","PMA_AU","PTOS_AU","BONO_AU","PTOS_TOTAL","BONOS_TOTAL","PORBONO_INTEGRAL","BONO_INTEGRAL","CONGRESO","FEC_ACT","FOLIO","RENOVADO","CANCELADO","REHABILITADA","BASE","CONSERVACION","POR_CONSERVACION","FEC_CORRIDA"]


try:
	start_time 	= time.time()

	# Setting variuables
	db_name = str(sys.argv[1])
	sh_name = str(sys.argv[2])
	file_di = str(sys.argv[3])
	status  = "success"

	# Start bd connection and set configurations
	connection 	= mysql.connector.connect(host='proages-db.coroolzydzjr.us-east-1.rds.amazonaws.com', database= db_name, user='proages', password='sjme17dkrmtl0km5p')
	cursor  	= connection.cursor()
	cursor.execute("SET autocommit = 0")

	# Start and set reader on sheet
	reader		= load_workbook(filename=file_di, read_only=True)
	sheet		= reader[sh_name]
	data 		= []
	stop 		= 0

	# Set sql insert query
	query = ""
	sheet_headers = []
	for cell in sheet[1]:
		sheet_headers.append(u""+cell.value)

	if sh_name == 'produccion_':
		query   = """INSERT INTO production_ (INTERL,INTER,FLA_NUM,POLIZA,APRI,FEMOVIBO,FEFIPEBO,IMPRI,PO_UBI1_DA,PO_PAG1_DA,DNNOM,PLAN,FECONTBO,INCREMEN,PERIODO) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""
		headers = get_array_keys_by_sheet("produccion_")
		keys    = get_list_indexes(headers,sheet_headers)
		stop  = 15

	if sh_name == 'produccion_sa':
   		query   = """INSERT INTO production_gmm_ (FECSIS, CLAVE, POLIZA_, ST_NUEREN, PMANETA, PMAUBI, PRIMAAFE, ASEGURADO, PLAN, PERIODO, TIPO_DEDUCIBLE) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""
		headers = get_array_keys_by_sheet("produccion_sa")
		keys    = get_list_indexes(headers,sheet_headers)
		stop  = 11

	if sh_name == 'conservacion_':
		query   = """INSERT INTO conservation_ (INTERL,INTER,TIPOHO,POLIZA1, NOMBRE,PLAN,FEC_EMIS,PAG_HASTA,AIO_POL ,PRIMA1,CONS_REAL1,CONS_ACOT1,PRISFP1, MONEDA1, FOR_PAGO1,PE_CON_ZONA, PERIODO) VALUES ( %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s, %s,%s,%s,%s)"""
		headers = get_array_keys_by_sheet("conservacion_")
		keys    = get_list_indexes(headers,sheet_headers)
		stop  = 17

	if sh_name == 'bona':
		query   = """INSERT INTO bona_t (PERIODO, INTER,NOMBRE,ZONA,ST,TIPO_GTE,FEC_CNSF,GENERACION , PAFECT1, PAFECT2, INCRE,PAFECT1_EXC, PAFECT2_EXC, PROD_BONO_1_UBI, PROD_BONO_1_PAG, PROD_BON_2_UBI,PROD_BON_2_PAG,NEGS_BONO,NEGS_BONO_PAG,BASEA,PconReal,PconAcot,BASE_CA,NPOL_CA, BASE_CP, NPOL_CP, BASE_PP, NPOL_PP, BASE_CO, NPOL_CO, BASE_RE, NPOL_RE, COL1,RENGLON1,PORBONO1,BONO1,COL2,RENGLON2,PORBONO2,BONO2,RENGLON3,PORBONO3,BONO3,PROD_TOT,PTOS_TOT,TABLA,TIPO_,MIN_CONS,BASE_MIN_CERO,BON_CRE, BXACOT,PRO_INC_ANT, PRO_INC_ACT, POR_INCREMENTO,PROD_NOV,PROD_CT, CONS_MIN,MES_CORTE,FEC_ACT, CONGRESO,FEC_CORRIDA, PUNTOS_DESARROLLO) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"""
		headers = get_array_keys_by_sheet("bona")
		keys    = get_list_indexes(headers,sheet_headers)
		stop  = 62

	if sh_name == 'negocios_':
		query   = """INSERT INTO business_ (INTERL,INTER,ASEGURADO,PLAN,PMA_PAGADA,PMA_ANUAL,neg_camp_mas_pai,NEG_PAGA,PERIODO) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)"""
		sheet_headers[6] = "neg_camp_mas_pai"
		headers = get_array_keys_by_sheet("negocios_")
		keys    = get_list_indexes(headers,sheet_headers)		
		stop  = 9

	if sh_name == 'puntos_':
		query   = """INSERT INTO points_ (PERIODO,ZONA,INTER,NOMBRE,GENERACION,RENGLON1,PROD_BONO_1_PAG,PTOS_TOT) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)"""
		headers = get_array_keys_by_sheet("puntos_")
		keys    = get_list_indexes(headers,sheet_headers)
		stop  = 8

	if sh_name == 'bona_vi':
		query   = """INSERT INTO bona_vi (PERIODO, INTER,FOLIO,NOMBRE,ZONA,ST,TIPO_GTE,FEC_CONEC,GENERACION,PAFECT1, PAFECT2, INCRE,PAFECT1_EXC ,PAFECT2_EXC, PROD_BONO_1_UBI, PROD_BONO_1_PAG, PROD_BON_2_UBI,PROD_BON_2_PAG,NEGS_BONO,BASEA,PconReal,PconAcot,NPOL_CA ,BASE_CA, NPOL_CP, BASE_CP, NPOL_PP, BASE_PP, NPOL_CO, BASE_CO, NPOL_RE, BASE_RE, COL1,RENGLON1,PORBONO1,BONO1,COL2,RENGLON2,PORBONO2,BONO2,BONO_TOT,PTOS_TOT,PTOS_INT_VI,TABLA,TIPO_,MIN_CONS,BASE_MIN_CERO,BON_CRE,BXACOT,CLUB_ELITE,BONO1_SA,PTOS_SA ,BONO1_AU,PTOS_AU, BONO_TOT_INT,PTOS_TOT_INT,PORBONO_INT ,BONO_INT,BONO_CONSTANCIA_NEGS,CONGRESO,FEC_ACT, TIPO_AGRUP,FEC_CORRIDA ,PUNTOS_DESARROLLO) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"""
		headers = get_array_keys_by_sheet("bona_vi")
		keys    = get_list_indexes(headers,sheet_headers)
		stop = 64

	if sh_name == 'bong_sa':
		query   = """INSERT INTO bong_sa (PERIODO,FOLIO,ZONA,NOMBRE,FEC_CONEC,GENERACION,ST,INTER,PMA_AFE1,PMA_AFE2,PMA_UBI1,PMA_UBI2,PMA_PAG1,PMA_PAG2,PMA_EXC1,PMA_EXC2,PMA_ANUAL,PMA_ULT_12,SINI_REAL,SINACO,PORSINI_REAL,PORSINI ,INDICADOR_PORSINI,RENGLON1,COLUMNA1,PORBONO1,BONO1,RENGLON2,COLUMNA2,PORBONO2,BONO2,BONO_TOTAL,PTOS_PROD,PORBONO_DESARROLLO,BONO_DESARROLLO,NUM_ASEG,TABLA,PMA_NOV,CONGRESO,FEC_ACT,CONS_VIDA,PMA_INI_VIDA,PMA_NOV_VIDA,FECHA_CORRIDA) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"""
		headers = get_array_keys_by_sheet("bong_sa")
		keys    = get_list_indexes(headers,sheet_headers)
		stop = 44

	if sh_name == 'siniestralidad_':
		query   = """INSERT INTO siniestralidad_ (FECHA,AGENTE,ASEGURADO,NUMRECL, POLIZA,TOTAL,SINACO_DA,PERIODO) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)"""
		headers = get_array_keys_by_sheet("siniestralidad_")
		keys    = get_list_indexes(headers,sheet_headers)
		stop  = 8

	if sh_name == 'asegurados_':
		query   = """INSERT INTO asegurados_ (PERIODO,LIDER,F_LIDER,ASEGURADO,NUMCERT,FEC_ANTIG,POLIZA) VALUES (%s,%s,%s,%s,%s,%s,%s)"""
		headers = get_array_keys_by_sheet("asegurados_")
		keys    = get_list_indexes(headers,sheet_headers)
		stop  = 7

	if sh_name == 'BONA_SA':
		query   = """INSERT INTO bona_sa (PERIODO,LIDER,NLIDER,FECCONEC,GENERACION,ZONA_PROV,TIPO_GTE,SUBGRUPO,ST_PRO,PMA_AFE1,PMA_UBI1,PMA_PAG1,PMA_EXC1,PMA_AFE2,PMA_UBI2,PMA_PAG2,PMA_EXC2,PMA_ANUAL,N_ASEG,SINI_REAL,SINACO_AGT,PMA_ULT_12,PORSINI_REAL,PORSINI,INDICADOR_PORSINI,RENGLON1,COLUMNA1,PORBONO1,BONO1,PTOS_PROD_DA,RENGLON2,COLUMNA2,PORBONO2,BONO2,PTOS,BONO_TOT,AGT_PROD,IND_PAGO,TABLA,RENGLON_VI,PMA_VI,PTOS_VI,BONO_VI,RENGLON_AU,PMA_AU,PTOS_AU,BONO_AU,PTOS_TOTAL,BONOS_TOTAL,PORBONO_INTEGRAL,BONO_INTEGRAL,CONGRESO,FEC_ACT,FOLIO,RENOVADO,CANCELADO,REHABILITADA,BASE,CONSERVACION,POR_CONSERVACION,FEC_CORRIDA) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"""
		headers = get_array_keys_by_sheet("BONA_SA")
		keys    = get_list_indexes(headers,sheet_headers)
		stop = 61
		
	index = 0
	for row in sheet.rows:
		row_values = []
		cells_read = 0
		for cell in row:
			if cells_read in keys:
				val = ""
				# el valor se mete en la bd
				if(isinstance(cell.value,unicode)):
					tmp = u""+cell.value
					tmp2 = tmp.replace(u"\ufffd", "")
					tmp3 = tmp2.replace(u"\u2550", "")
					tmp4 = tmp3.replace(u"\u2551", "")
					tmp5 = tmp4.replace(u"\u2554", "")
					val = tmp5.replace(u"\u2534", "").encode("utf-8")
				else:
					val = cell.value
				row_values.append(val)
			cells_read= cells_read+1
				
		if index != 0:
			if len(row_values) > 0:
				cursor.execute(query, row_values)
		index=+1

	connection.commit()
	cursor.close()
	connection.close()
except mysql.connector.Error as error :
	status = format(error)
finally:
	print(status)