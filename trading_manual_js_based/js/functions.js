
///////////////////////// BOLLINGER BANDS ///////////////////////////////////////////////////	
	
	//calculate the bollinger bands
	//returns a multi array
	//bb[0] -> Middle Band = 20-day simple moving average (SMA)
	//bb[1] -> Upper Band = 20-day SMA + (20-day standard deviation of price x 2)
	//bb[2] -> Lower Band = 20-day SMA - (20-day standard deviation of price x 2)
	function BB(values, period=20, n_std=2){//values = array of values to calculate, period(optional) = time period, n_std(optional) = standard deviation offset
		
		//middle band = simple moving average
		let middle_band = SMA(values);

		//The map() method creates a new array with the results of the function for every array element (val).
		//val = is the value of the current element, index = the current index of the element, arr = the original array 'values'
		//Upper Band = 20-day SMA + (20-day standard deviation of price x 2)
		let upper_band = values.map((val, index, arr) => {
		
			//series to calculate the band < the period will return undefined as there are not enough elements to calculate
			//we set these results to 'null' as this is readable for most applications
			if(index < period){
				val = null;
			}else{
			
				//make a sub array with the values to be calculated
				//index-period = position where to begin the extraction. First value is at position 0 
				//index = The position (up to, but not including) where to end the extraction
				val = arr.slice(index-period, index);
				
				//calculate the moving average
				let avg = AVG(val);
				
				//calculate the standard deviation
				let std = STD(val);
				
				//calculate the upper band 
				val = avg+(std*n_std);
			}
			
			//return each result to the upper_band array
			return val;
		
		},0);
	
	
		//The map() method creates a new array with the results of the function for every array element (val).
		//val = is the value of the current element, index = the current index of the element, arr = the original array 'values'
		//Lower Band = 20-day SMA - (20-day standard deviation of price x 2)
		let lower_band = values.map((val, index, arr) => {
		
			//series to calculate the band < the period will return undefined as there are not enough elements to calculate
			//we set these results to 'null' as this is readable for most applications
			if(index < period){
				val = null;
			}else{
			
				//make a sub array with the values to be calculated
				//index-period = position where to begin the extraction. First value is at position 0 
				//index = The position (up to, but not including) where to end the extraction
				val = arr.slice(index-period, index);
				
				//calculate the moving average
				let avg = AVG(val);
				
				//calculate the standard deviation
				let std = STD(val);
				
				//calculate the lower band 
				val = avg-(std*n_std);
			}
			
			//return each result to the lower_band array
			return val;
		
		},0);

		//make multi dimensional array and return
		let bb = {middle: middle_band, high: upper_band, low: lower_band};
		return bb;
		
	}



////////////////////// STANDARD DEVIATION ///////////////////////////////////////////////////

	//calculate the standard deviation
	//returns single value, the standard deviation
	//STANDARD DEVIATION = the square root of the VARIANCE
	//VARIANCE = the average of the SQUARED DIFFERENCES from the MEAN (average)
	function STD(values){//values = array of values to calculate
	
		// call the avg() function to get average of the series
		let mean = AVG(values);
		
		//squared differences
		//The map() method creates a new array 'squaredDifferences' with the results of the function for every array element (val).
		let squaredDifferences = values.map((val) => {
			let difference = val - mean;//difference between mean and price
			let squaredDifference = difference * difference;
			return squaredDifference;
		},0);

		//variance
		//call the AVG() function to get the average of the squared differences
		let variance = AVG(squaredDifferences);

		//standard deviation
		//pass the variance to the sqrt() method from the Javascript Math object to calculate the square root
		let std = Math.sqrt(variance);
		
		return std;
	}

	
/////////////////////// EXPONENTIAL MOVING AVERAGE /////////////////////////////////////////////

	//calculate the exponential moving average
	//returns array with the ema values
	//EXPONENTIAL MOVING AVERAGE = (closeprice - previous ema) * multiplier + previous ema
	//initial ema = average of values from teh period subset
	//multiplier = 2/period+1);
	function EMA(values, period=9){//values = array of values to calculate, period is the timeperiod
		
		let multiplier = 2/(period+1);//ex. period = 10 -> result is 0.1818 (18.18%)
		let prev_ema = null;//store the previous ema
		
		period = period-1;//array index starts with 0. ex. period = 10 -> 10-1=9 (0,1,2,3,4,5,6,7,8,9) = 10 indexes
		
		//The map() method creates a new array 'ema' with the results of the function for every array element (val).
		let ema = values.map((val, index, arr) =>  {//val = current value, index =  current index, arr = original array (values)

			//series to calculate the ema < the period will return undefined as there are not enough elements to calculate
			//we set these results to 'null' as this is readable for most applications
			if(index < period){
				val = null;
			}else{
				
				if(index == period){
					//calculate sma
					//make a sub array with the values to be calculated
					//index-period = position where to begin the extraction. First value is at position 0 
					//index+1 = The position (up to, but not including) where to end the extraction
					let sub_arr = arr.slice(index-period, index+1);
					
					//calculate the initial sma witch is the average of the subset
					val = AVG(sub_arr);
					
					
				}else{
					//calculate ema
					//(closeprice - previous ema) * multiplier + previous ema
					val = (val-prev_ema)*multiplier+prev_ema;
				}
				
				prev_ema = val;//store ema for next calculation
				 
			}
			
			//return each result to the sma array
			return val;
		},0);
		
		return ema;
	}
	
	
	
/////////////////////// SIMPLE MOVING AVERAGE //////////////////////////////////////////////////

	//calculate the simple moving average
	//returns a new array with the moving averages
	//values = array of values to calculate, period = time period
	function SMA(values, period=20){
		
		period = period-1;//array index starts with 0. ex. period = 10 -> 10-1=9 (0,1,2,3,4,5,6,7,8,9) = 10 indexes
		
		//The map() method creates a new array with the results of the function for every array element (val).
		//val = is the value of the current element, index = the current index of the element, arr = the original array 'values'
		let sma = values.map((val, index, arr) =>  {
		
			//series to calculate the sma < the period will return undefined as there are not enough elements to calculate
			//we set these results to 'null' as this is readable for most applications
			if(index < period){
				val = null;
			}else{
			
				//make a sub array with the values to be calculated
				//index-period = position where to begin the extraction. First value is at position 0 
				//index+1 = The position (up to, but not including) where to end the extraction
				val = arr.slice(index-period, index+1);
				
				// call the AVG() function to get average of the sub series
				val = AVG(val);
			}
			
			//return each result to the sma array
			return val;
		},0);
		
		return sma;
	}

	
///////////////////// AVERAGE ///////////////////////////////////////////////////////////////////
	
	//calculate the average value of a series of values
	function AVG(values){
		
		//get the sum of the series
		//reduce method reduces the array to a single value
		//accumulator = the previously returned value, currValue = the value of the current element
		let sum = values.reduce((accumulator, currValue) => {
			return accumulator +  currValue;
		},0);
		
		// average is the sum of the elemensts / amount of elemensts
		let avg = sum/values.length;
		return avg;
	}
