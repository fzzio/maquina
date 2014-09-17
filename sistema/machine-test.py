#!/usr/bin/env python2
# -*- coding: utf-8 -*-

import json, urllib, urllib2, re, time, serial

archivo220v = open('./producto220v.txt', 'r')
archivo220v.close()

while True:
    print '----------------------------------'
    #code = raw_input('Código: ') 
    entrada = raw_input('\t\tCódigo: ')
    try:
        code = int(entrada)
    except ValueError:
        continue

    print code


    print '----------------------------------\n'