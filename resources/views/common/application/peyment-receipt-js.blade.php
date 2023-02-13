<script>
    $("#printReceipt").on("click", function(){
        var htmlToPrint = "";
        htmlToPrint += $("#printTable").html();
        // htmlToPrint += divToPrint.outerHTML;
        newWin = window.open("");
        // newWin.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">');
        // newWin.document.appends('<style>@media print{ .dont-print{display:none;}}');
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();
    });
</script>