function validateForm()
{
    var surname=document.forms["contact"]["surname"].value;
    var email=document.forms["contact"]["email"].value;
    var message=document.forms["contact"]["message"].value;

    if (surname==null || surname=="" || email==null || email=="" || message==null || message=="")
      {
          document.getElementById('formError').style.visibility = 'visible';
          return false;
      }
}
