// Set up the form behavior after the page loads.
document.addEventListener("DOMContentLoaded", function () {
   var form = document.getElementById("mainForm");
   var hilightableControls = document.querySelectorAll(".hilightable");
   var requiredControls = document.querySelectorAll(".required");

   // Apply the focus style.
   function addHighlight(event) {
      event.target.classList.add("highlight");
   }

   // Remove the focus style.
   function removeHighlight(event) {
      event.target.classList.remove("highlight");
   }

   // Clear the error style when the field has content.
   function removeError(event) {
      if (event.target.value.trim() !== "") {
         event.target.classList.remove("error");
      }
   }

   // Block submission if a required field is empty.
   function validateRequired(event) {
      var hasErrors = false;
      var i;

      for (i = 0; i < requiredControls.length; i += 1) {
         var control = requiredControls[i];

         if (control.value.trim() === "") {
            control.classList.add("error");
            hasErrors = true;
         } else {
            control.classList.remove("error");
         }
      }

      if (hasErrors) {
         event.preventDefault();
      }
   }

   for (var i = 0; i < hilightableControls.length; i += 1) {
      var control = hilightableControls[i];

      control.addEventListener("focus", addHighlight);
      control.addEventListener("blur", removeHighlight);
   }

   for (var j = 0; j < requiredControls.length; j += 1) {
      var requiredControl = requiredControls[j];

      requiredControl.addEventListener("input", removeError);
      requiredControl.addEventListener("change", removeError);
   }

   form.addEventListener("submit", validateRequired);
});
