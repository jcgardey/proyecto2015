var wrapperOffset = 30;
function wrapElement(element) {
	var rectangle = element.getBoundingClientRect();
	var canvas = document.createElement('canvas');			
	canvas.style.position='absolute';
	canvas.style.zIndex=100000;
	canvas.style.left = rectangle.left - wrapperOffset + "px";
	canvas.style.right = rectangle.right + wrapperOffset + "px";
	canvas.style.top = rectangle.top - wrapperOffset + "px";
	canvas.style.bottom = rectangle.bottom + wrapperOffset + "px";

	canvas.width = (rectangle.right + wrapperOffset) - (rectangle.left - wrapperOffset);
	canvas.height = (rectangle.bottom + wrapperOffset) - (rectangle.top - wrapperOffset);
	element.parentElement.insertBefore(canvas, element);
	$(canvas).attr('data-type', element.tagName.toLowerCase());
	canvas.id = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 7);
	return canvas;
}

function wrapElements() {
	$("a, input, select").each(function(i, element) {
		console.log(element);
		wrapElement(element);
	});
}

function HoverTime() {
	var mouseOverTime = 0;
	$("canvas[data-type='input'], canvas[data-type='a'], canvas[data-type='select']").on("mouseover", function(e) {
		mouseOverTime = e.timeStamp;
	});
	$("canvas[data-type='input'], canvas[data-type='a'], canvas[data-type='select']").on("mouseout", function(e) {
		var now = e.timeStamp;
		console.log("Hover time " + (now - mouseOverTime));
		mouseOverTime = now;
	});


}
