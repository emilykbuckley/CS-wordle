<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Emily Buckley">
        <meta name="description" content="Word Game">  
        <title>Game Over</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous"> 
    </head>
    <body>
        <div class="container" style="margin-top: 15px;">
            <div class="row col-xs-8">
                <h1>Game Over</h1>
                <h3><?=$message?> <?=$user["name"]?>!</h3>
            </div>
            <div class="row">
                <div class="col-xs-8 mx-auto">
                <form action="?command=question" method="post">
                    <div class="h-100 p-5 bg-light border rounded-3">
                    <h2>The correct word was: <?=$user["word"]?></h2>
                    <p>You took <?=$guessmsg?></p>
                    </div>
                    <div class="text-center">                
                    <a href="?command=newgame" class="btn btn-primary">Play again</a>
                    <a href="?command=logout" class="btn btn-danger">Exit</a>
                    </div>
                </form>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    </body>
</html>