#!/usr/bin/env python2
# -*- coding: utf-8 -*-

import json, urllib, urllib2, re, time, serial

totalMotores = 20
productoXMotor = 5

listaProducto = [productoXMotor for i in range(totalMotores)]
totalBotellas = listaProducto.__len__() * 5


datoOk = "\x06"
datoNo = "\x15"

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
    archivo220v = open('producto220v.txt', 'w')
    archivo220v.write(contenidoActual)
    archivo220v.close()
    return True

def cargarDeArchivo():
    archivo220v = open('producto220v.txt', 'r')
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
    archivo220v = open('producto220v.txt', 'w')
    archivo220v.write(contenidoActual)
    archivo220v.close()

    cargarDeArchivo()
    return True


cargarDeArchivo()


while True:
    print listaProducto
    print '----------------------------------'
    #code = raw_input('Código: ') 
    code = int( raw_input('\t\tCódigo: ') )

    #print code

    if code == 0:
        # Error, pedir código de nuevo
        print 'Error: Código inexistente'
        continue

    elif code == 1:
        #Enviar codigo por usb, esperar ack
        #2 bandejas de 10 motores cada uno. 5 Productos por motor

        for i in xrange(0, listaProducto.__len__() ):
            if( listaProducto[i] > 0 and listaProducto[i] <= 5):
                #Existe producto, hago peticion al motor
                stringMotor = ("%d" % (i + 1)).rjust(2, '0')

                print ('\tDisponible en motor  #%s: %d botellas.' % ( stringMotor , listaProducto[i]))
                print ('\tSolicitando al motor  #%s...' % ( stringMotor ))

                #stringTramaMotor = "\xee\x01\x55\xaa"
                stringTramaMotor =  ( r"\xee\x%s\x55\xaa" % stringMotor ).decode('string-escape')
                print ('\tLa trama es: %s' % ( stringTramaMotor ))

                ser.write(stringTramaMotor )

                inp = ser.read() #read a byte
                #print inp.encode("hex") #gives me the correct bytes, each on a newline 

                if datoOk == inp:
                    print "\tDespachado"
                    print "\t****Gracias " + "Campusero" + " por asistir.****"
                    listaProducto[i] = listaProducto[i] - 1

                    grabarArchivo()

                elif datoNo == inp:
                    print "\tError en despacho, intentaremos en siguiente motor..."
                    continue

                break
            elif(listaProducto[i] == 0 and ( listaProducto.__len__()-1 ) != i ):
                print "\tColumna #%s vacia, cambiando siguiente motor..." % (stringMotor)
                continue
            

            print "\t¡Alerta!: La maquina esta vacía. Recargando..."
            #se manda en caso de ser necesario una señal al beagle
            resetearArchivo()
        
        pass

    elif code == 2:
        # TODO mostrar en algún lugar que premio ya fue reclamado

        print 'Gracias por asistir, previamente has retirado tu producto'

    print '----------------------------------\n'