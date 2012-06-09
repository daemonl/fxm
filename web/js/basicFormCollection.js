
  function addToCollectionForm(EleID) {
    // Get the div that holds the collection of tags
    var collectionHolder = $("#"+EleID);
    // Get the data-prototype we explained earlier
    var prototype = collectionHolder.attr('data-prototype');
    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on the current collection's length.
    form = prototype.replace(/\$\$name\$\$/g, collectionHolder.children(".formCollection-ul").children().length);
    // Display the form in the page
    collectionHolder.children(".formCollection-ul").append(form);
  }

$(document).on("click", ".formCollection-add", function(event) {
  event.stopPropagation();
  event.preventDefault();
  addToCollectionForm($(this).attr('collectionId')); 
});

$(document).on("click", ".formCollection-remove", function(event) {
  event.stopPropagation();
  event.preventDefault();
  if (confirm("Are you sure you want to remove this?") == true)
    {
      $(this).parent().parent().remove();
    }
});