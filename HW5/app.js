const express= require("express");
const parser= require("body-parser");
const path=require('path');
app = express();
app.use(parser.urlencoded({ extended: true }));
app.set("view engine", "ejs");
app.use('views', express.static(path.join(__dirname, 'views')));
var listener = app.listen(8000, function(){
    console.log('Listening on port ' + listener.address().port); 
});

app.get('/',function(req,res){
    res.render('ex');
});
app.post('/ex',function(req,res){
    res.render('show',{name:req.body.name, surname:req.body.surname})
});