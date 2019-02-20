cd /Users/bluel/crypto

::start http://localhost/php_py/

::FOR /L %%A IN (1,1,20) DO (
  
 :: timeout 1
::)


FOR /L %%N IN () DO (

	Ping www.google.nl -n 1 -w 1000
	cls
	if errorlevel 1 (
	
		set internet=Not connected to internet
		netsh wlan disconnect
		netsh wlan connect name="HUAWEI P8 lite" 
	
	)

	 python create_data_5m.py
	 timeout 120
)
