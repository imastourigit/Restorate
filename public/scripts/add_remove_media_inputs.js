document.addEventListener('DOMContentLoaded', () => {
 
const addFormToCollection = (e) => {
 
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
    const item = document.createElement('li');  
    item.innerHTML = collectionHolder
      .dataset
      .prototype
      .replace(
        /__name__/g,
        collectionHolder.dataset.index
      );
        collectionHolder.appendChild(item);
    
        collectionHolder.dataset.index++;
        addTagFormDeleteLink(item);  
                        };
      

  
  const addTagFormDeleteLink = (item) => {
      const removeFormButton = document.createElement('button');
      removeFormButton.innerText = 'Remove Image';
          removeFormButton.className = 'btn btn-danger';
  
      item.append(removeFormButton);
  
      removeFormButton.addEventListener('click', (e) => {
          e.preventDefault();
          // remove the li for the tag form
          item.remove();
      });
  }

  document.querySelectorAll('button.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });
 
 
  document.querySelectorAll('ul.media li')
      .forEach((media) => {
          addTagFormDeleteLink(media)
      });  

 
    
    });