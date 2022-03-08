<style>
	#f_menu{
		position:absolute;
		bottom:0px;
		left:10px;
		list-style-type:none;
		z-index:1000;
	}
	#f_menu > ul{
		display:inline-block;
		background-color:#5f6368;
		color:white;
		text-align:center;
		padding:10px 10px 10px 10px;
		border-radius:5px;
		margin-right:5px
	}
	#f_menu > ul:hover{
		cursor:pointer;
		color:white;
		filter: brightness(1.1);
	}
</style>

<li id="f_menu">
<ul>Convert</ul>
<ul>Attendance</ul>
</li>

<script>	
	let saveImage
	let classlist = []
	let malelist = []
	let femalelist = []
	
	let findVideo = () => {
		let v
		document.querySelectorAll('video').forEach((e,i)=>{
			//console.log(e.videoHeight)
			if(e.videoHeight>500) v = e
		})
		return v
	}
	
	let saveVideo = () => {
		let presentVideo = findVideo()
		const canvas = document.createElement("canvas");
		canvas.width = presentVideo.videoWidth;
		canvas.height = presentVideo.videoHeight;
		canvas.getContext('2d').drawImage(presentVideo, 0, 0, canvas.width, canvas.height);
		saveImage = canvas.toDataURL();
		const dataURL = canvas.toDataURL();
	}

	let postImage = () => { 
		saveVideo()
		fetch("http://localhost/Image2Text/img2text.php",{                  
			method: "POST",  
			headers: {'Content-Type': 'application/json'},
			body: saveImage
		})
		.then(res => res.json())
		.then(data => {
			textA.value = data.message
			console.log(data)
		})      
	}
	
	let getAttendance = () => {
		classlist = []
		document.querySelectorAll("[role=listitem]").forEach((e)=>{
			var fullname= e.querySelector("div span").innerText
			if(fullname.search(",") == -1){
				var arrfn = fullname.split(" ")
				var surn = arrfn.pop()
				classlist.push((surn + ", " + arrfn.toString().replace(/,/g," ")).toUpperCase())
				e.querySelector("div span").innerText = surn + ", " + arrfn.toString().replace(/,/g," ")
			}else{
				classlist.push(fullname.toUpperCase())
			}
		})
		
		classlist.sort()
		classlist.push("Item: " + classlist.length)
		textA.value = classlist.join('\r\n')
	}
	
	const textA = document.createElement('textarea')
	//textA.id = "textA"
	textA.style = "position:absolute;top:0;left:0;z-index:1000;width:300;height:200"
	document.body.appendChild(textA)
	 
	/***************************************************************/
	let f_menu_css = document.createElement('style')
	f_menu_css.innerHTML = `
		#f_menu{
			position:absolute;
			bottom:0px;
			left:10px;
			list-style-type:none;
			z-index:1000;
		}
		#f_menu > ul{
			display:inline-block;
			background-color:#5f6368;
			color:white;
			text-align:center;
			padding:10px 10px 10px 10px;
			border-radius:5px;
			margin-right:5px
		}
		#f_menu > ul:hover{
			cursor:pointer;
			color:white;
			filter: brightness(1.1);
		}`
	document.head.appendChild(f_menu_css)
	
	let f_menu = document.createElement('li')
	f_menu.id = "f_menu"
	let f_convert = document.createElement('ul')
	f_convert.innerText = "Convert"
	f_convert.addEventListener('click',()=>{
		console.log("CONVERT")
		postImage()
	})
	let f_attendance = document.createElement('ul')
	f_attendance.innerText = "Attendance"
	f_attendance.addEventListener('click',()=>{
		console.log("ATTENDANCE")
		getAttendance()
	})
	
	f_menu.appendChild(f_convert)
	f_menu.appendChild(f_attendance)
	document.body.appendChild(f_menu)
</script>