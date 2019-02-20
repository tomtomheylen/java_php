 


FOR /L %%N IN () DO (

	Ping www.google.nl -n 1 -w 1000
	cls
	if errorlevel 1 (
	
		set internet=Not connected to internet
		netsh wlan disconnect
		netsh wlan connect name="HUAWEI P8 lite" 
	
	) else (
	
		set internet=Connected to internet
		
	)

	echo %internet%
	


 timeout 3
)

