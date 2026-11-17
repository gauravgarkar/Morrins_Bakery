


const contactForm = document.querySelector("#contact_from");
const formMessage = document.querySelector("#formMessage");

const namePattern=  /^[\p{L}\p{M}]+(?:[ '\u2019-][\p{L}\p{M}]+)*$/u;


contactForm.addEventListener("submit", function (event) {
  

  const formData = new FormData(contactForm);
  const name = formData.get("name").trim();
  const email=formData.get("email").trim();
  const phone=formData.get("phone").trim();
 if(!namePattern.test(name))
{
  formMessage.textContent="plsease Enter a name using letters,Spaces,hypens and apostropys are okay ";
  
 event.preventDefault(); 
  return;
}

const domain=email.slice(email.lastIndexOf("@") + 1);
const lastDot = domain.lastIndexOf(".");
if (lastDot <= 0 || lastDot === domain.length - 1) { 
   event.preventDefault();  
  formMessage.textContent =
      "Please enter an email with a domain, such as name@gmail.com.";
    
    return;
  }
  
}); 

window.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);

  if (params.get("success") === "1") {
    alert("Contact submitted successfully!");

    // Remove the success flag so refreshing doesn't show the alert again.
    window.history.replaceState(
      null,
      "",
      window.location.pathname + window.location.hash
    );
  }
});