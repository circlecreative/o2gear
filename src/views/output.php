<html>
<head>
    <title>
        PHP Developer Tools - Debugger
    </title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- Bootstrap Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.css">

    <!-- Font Icon CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <style type="text/css">

        body { font-family: Monaco, Consolas, "Lucida Console", monospace; margin:25px; }
        .copyright { padding-top:10px; color:#790000; }
        pre { font-family: Monaco, Consolas, "Lucida Console", monospace; margin-top:20px; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="page-header">
    <h1>O2System Developer Tools</h1>
</div>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title" style="text-transform:uppercase">Print Out</h3>
            <button onclick="javacript:selectCode('code')" class="btn btn-xs pull-right">Select Code</button>
        </div>

        <div class="panel-body">
            <pre id="code" class="prettyprint linenums lang-html"><?php echo $data; ?></pre>
        </div>
    </div>
</div>

<?php if(isset($tracer)): ?>
    <div class="row">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title" style="text-transform:uppercase">Debug Backtrace</h3>
            </div>

            <div class="panel-body">
                <?php echo $tracer; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="copyright">Copyright &copy 2009 - <?php echo date('Y'); ?> PT. Lingkar Kreasi (Circle Creative) :: O2Gears</div>
</body>

<!-- Include jQuery -->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

<!-- Include Prettify JS -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>

<!-- Include Bootstrap -->
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<!-- Initialise jQuery Syntax Highlighter -->
<script type="text/javascript">
    $(document).ready(function(){
        prettyPrint();

        $('.toggle-args').click(function(){
            var iRel = $(this).attr('rel');
            $('#'+iRel).toggle('slow');
        });
    });

    function selectCode(id) {
        if (document.selection) {
            var div = document.body.createTextRange();
            div.moveToElementText(document.getElementById(id));
            div.select();
        }
        else {
            var div = document.createRange();
            div.setStartBefore(document.getElementById(id));
            div.setEndAfter(document.getElementById(id));
            window.getSelection().addRange(div);
        }
    }
</script>
</html>