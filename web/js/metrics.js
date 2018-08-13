var wrapperOffset = 20;
function wrapElement(element) {
	var rectangle = element.getBoundingClientRect();
	var wrapper = document.createElement('div');			
	wrapper.style.position='absolute';
	wrapper.style.zIndex= 999;
	wrapper.style.left = rectangle.left - wrapperOffset + "px";
	wrapper.style.right = rectangle.right + wrapperOffset + "px";
	wrapper.style.top = rectangle.top - wrapperOffset + "px";
	wrapper.style.bottom = rectangle.bottom + wrapperOffset + "px";

	wrapper.style.width = (rectangle.right + wrapperOffset) - (rectangle.left - wrapperOffset) + "px";
	wrapper.style.height = (rectangle.bottom + wrapperOffset) - (rectangle.top - wrapperOffset) + "px";
	element.parentElement.insertBefore(wrapper, element);
	
	$(wrapper).attr('data-type', element.tagName.toLowerCase());
	wrapper.id = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 7);

	element.style.position = 'relative';
	element.style.zIndex = 99999;
	
	return wrapper;
}

function wrapElements() {
	$("a, input[type='text'], input[type='password'], select").each(function(i, element) {
		wrapElement(element);
	});
}

function HoverTime() {
	var lastMouseMove = 0;
	var lastElement = null;
	var threshold = 1000;
	var inactiveTime = 0;
	var moveTime = 0;
	$("div[data-type='input'], div[data-type='a'], div[data-type='select'],a, input[type='text'], input[type='password'], select").on("mousemove", function (e) {	
		var now = e.timeStamp;
		console.log("cantidad " + (now - lastMouseMove));
		if ((now - lastMouseMove) >= threshold) {
			inactiveTime += (now - lastMouseMove);
		}
		else {
			moveTime += (now - lastMouseMove);
		}
		lastMouseMove = now;
	});

	$("div[data-type='input'], div[data-type='a'], div[data-type='select']").on("mouseleave", function(e) {

		// estoy pasando del wrapper al elemento, por lo tanto no se debe resetear el contador
		if (e.relatedTarget == e.target.nextSibling) {
			return null;
		} 
		console.log("Mouse move time " + moveTime );
		console.log("Mouse inactive time " + inactiveTime);
		moveTime = 0;
		inactiveTime = 0;
	});


}
