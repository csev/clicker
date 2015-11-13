var time = 0;
var running = false;

function startPause(){

	if(!running){

		running = true;
		increment();
		document.getElementById("startPause").innerHTML = "Pause";

	}
	else{

		running = false;
		document.getElementById("startPause").innerHTML = "Resume";
	}
}

function reset(){

	running = false;
	time = 0
	document.getElementById("startPause").innerHTML = "Start";
	document.getElementById("timerOutput").innerHTML = "00:00";
}

function increment(){

	if(running){

		setTimeout(function (){

			time ++;

			var secs = Math.floor(time / 10 % 60);
			var mins = Math.floor(time / 10 / 60);

			if(secs < 10)
				secs = "0" + secs;

			if(mins < 10)
				mins = "0" + mins;

			document.getElementById("timerOutput").innerHTML = mins + ":" + secs;
			increment()
		},100)
	}
}

