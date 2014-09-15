#!/usr/bin/env python2
# -*- coding: utf-8 -*-

import json, urllib, urllib2, re, time, serial

totalMotores = 20
productoXMotor = 5

listaProducto = [productoXMotor for i in range(totalMotores)]
totalBotellas = listaProducto.__len__() * 5


datoOk = "\x06"
datoNo = "\x15"
datoDespachado = "\x16"

# configure the serial connections (the parameters differs on the device you are connecting to)
ser = serial.Serial(
    port='/dev/ttyUSB0',
    baudrate=9600,
    parity=serial.PARITY_NONE,
    stopbits=serial.STOPBITS_ONE,
    bytesize=serial.EIGHTBITS
)



def grabarArchivo():
     #almacenamos en archivo de backup
    contenidoActual = ''
    for y in xrange(0,listaProducto.__len__() ):
        contenidoActual += "%d," % listaProducto[y]
    #print contenidoActual
    archivo220v = open('./producto220v.txt', 'w')
    archivo220v.write(contenidoActual)
    archivo220v.close()
    return True

def cargarDeArchivo():
    archivo220v = open('./producto220v.txt', 'r')
    arregloDatos = archivo220v.readline().split(',')
    archivo220v.close()
    arregloDatos.pop()
    for x in xrange(0,totalMotores):
        listaProducto[x] = int(arregloDatos[x])
    return True

def resetearArchivo():
     #almacenamos en archivo de backup
    contenidoActual = ''
    for y in xrange(0,listaProducto.__len__() ):
        contenidoActual += "%d," % productoXMotor
    #print contenidoActual
    archivo220v = open('./producto220v.txt', 'w')
    archivo220v.write(contenidoActual)
    archivo220v.close()

    cargarDeArchivo()
    return True


cargarDeArchivo()


while True:
    print listaProducto
    print '----------------------------------'
    #code = int( raw_input('\t\tCódigo: ') )
    entrada = raw_input('\t\tCódigo: ') 
    try:
        code = int(entrada)
    except ValueError:
        continue

    retries = 1
    while retries <= 3:
        try:
            r = urllib2.urlopen(
                'http://www.220v.ec/campus-party/verificar.php',
                urllib.urlencode({'txtCodigo': code}),
                3,
            )

            data = r.read()
            break
        except:
            # En caso de error intentar nuevamente, hasta 3 veces
            print 'Error requesting data, trying again...'
            retries += 1
    # Pedir codigo nuevamente al agotar 3 intentos
    else:
        continue

    try:
        data = json.loads(data)

        if not 'codigo' in data:
            raise Exception()
    except:
        # Mala respuesta
        continue

    print data

    if data['codigo'] == 0:
        # Error, pedir código de nuevo
        print 'Error: Código inexistente'
        continue

    elif data['codigo'] == 1:
        #Enviar codigo por usb, esperar ack
        #2 bandejas de 10 motores cada uno. 5 Productos por motor

        i = 0
        reintentoXMotor = 0
        while i < listaProducto.__len__():
            stringMotor = ("%d" % (i + 1)).rjust(2, '0')

            if( listaProducto[i] > 0 and listaProducto[i] <= 5):
                #Existe producto, hago peticion al motor                
                print ('\tDisponible en motor  #%s: %d botellas.' % ( stringMotor , listaProducto[i]))
                print ('\tSolicitando al motor  #%s...' % ( stringMotor ))

                #stringTramaMotor = "\xee\x01\x55\xaa"
                stringTramaMotor =  ( "\xee" + chr(i + 1) + "\x55\xaa" ).decode('string-escape')
                print ('\tLa trama es: %s' % ( stringTramaMotor ))

                ser.write(stringTramaMotor )

                inp = ser.read() #read a byte
                #print inp.encode("hex") #gives me the correct bytes, each on a newline 

                if datoOk == inp:
                    print "\tTrama entregada, esperando confirmación de motor..."
                    inp2 = ser.read() #read a byte

                    if datoDespachado == inp2:
                        print "\tDespachado"
                        print "\t****Gracias " + data['nombre'] + " por asistir.****"
                        listaProducto[i] = listaProducto[i] - 1
                        grabarArchivo()
                        i = i + 1
                    else:
                        print "\tError en despacho, intentaremos en siguiente motor..."
                        i = i + 1
                        continue

                elif datoNo == inp:
                    print "\tError en comunicacion, reenviando trama..."
                    reintentoXMotor = reintentoXMotor + 1
                    if reintentoXMotor >= 3:
                        print "\tError en comunicación, desechando..."
                        break
                        
                    continue

                else:
                    print "\tRecibió ruido, desechando..."
                    break

                break
            elif(listaProducto[i] == 0 and ( listaProducto.__len__()-1 ) != i ):
                print "\tColumna #%s vacia, cambiando siguiente motor..." % (stringMotor)
                i = i + 1
                continue
            

            print "\t...¡Alerta!: La máquina esta vacía. Recargando..."
            #se manda en caso de ser necesario una señal al beagle
            resetearArchivo()
            i = 0
            continue
        
        pass

    else:
        pass

    print '----------------------------------\n'