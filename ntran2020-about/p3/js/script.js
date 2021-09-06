function myMax()
{
  var a, b, c, temp;
  a = parseInt(document.getElementById("num1").value);
  b = parseInt(document.getElementById("num2").value);
  c = parseInt(document.getElementById("num3").value);
  temp = Math.max(a,b,c);
  console.log(temp);
  alert("The MAX of the three numbers is " +temp);
  document.getElementById("max").innerHTML = "The MAX is "+temp;
  return(temp);
}
function myMin()
{
    var a, b, c, temp;
    a = parseInt(document.getElementById("num1").value);
    b = parseInt(document.getElementById("num2").value);
    c = parseInt(document.getElementById("num3").value);
    temp = Math.min(a,b,c);
    console.log(temp);
    alert("The MIN of the three numbers is " +temp);
    document.getElementById("min").innerHTML = "The MIN is "+temp;
    return(temp);
}
function myMean()
{
    var a, b, c, temp;
    a = parseInt(document.getElementById("num1").value);
    b = parseInt(document.getElementById("num2").value);
    c = parseInt(document.getElementById("num3").value);
    temp = (a+b+c)/3;
    console.log(temp);
    alert("The MEAN of the three numbers is " +temp);
    document.getElementById("mean").innerHTML = "The MEAN is "+temp;
    return(temp);
}
function myMedian()
{
   var a, b, c, temp;
    a = parseInt(document.getElementById("num1").value);
    b = parseInt(document.getElementById("num2").value);
    c = parseInt(document.getElementById("num3").value);
   var ar = [a,b,c];
    ar.sort(function(a, b){return a-b;});
    console.log(ar[1]);
    alert("The MEDIAN of the three numbers is " +ar[1]);
    document.getElementById("median").innerHTML = "The MEDIAN is "+ar[1];
    return(ar[1]);
}
function myRange()
{
  var a, b, c, temp;
    a = parseInt(document.getElementById("num1").value);
    b = parseInt(document.getElementById("num2").value);
    c = parseInt(document.getElementById("num3").value);
   var ar = [a,b,c];
    ar.sort(function(a, b){return a-b;});
    temp = ar[2] - ar[0];
    console.log(temp);
    alert("The RANGE of the three numbers is " +temp);
    document.getElementById("range").innerHTML = "The RANGE is "+temp;
    return(temp);
}
function myCal()
{
    myMax();
    myMin();
    myMean();
    myMedian();
    myRange();
}