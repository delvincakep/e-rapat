$(document).ready(function () {
	$("#dataTable").DataTable({
		dom: "Bfrtip",
		buttons: ["print", "pdfHtml5"],
	});

	var table = $("#meeting").DataTable({
		// scrollY: "500px",
		paging: true,
		order: [[0, "DESC"]],
		// dom: "Bfrtip",
		// buttons: ["print", "pdfHtml5"],
	});

	$("a.toggle-vis").on("click", function (e) {
		e.preventDefault();

		// Get the column API object
		var column = table.column($(this).attr("data-column"));

		// Toggle the visibility
		column.visible(!column.visible());
	});
	$("#freeRoom").DataTable({
		lengthMenu: [
			[5, 15, 30, -1],
			[5, 10, 15, "All"],
		],
		// scrollY: 700,
		pageLength: 5,
	});
	$("#dataHistory").DataTable({
		lengthMenu: [5, 10, 15],
		dom: "Bfrtip",
		scrollY: 300,
	});
});
