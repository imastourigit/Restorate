document.addEventListener('DOMContentLoaded', () => {
let links= document.querySelectorAll('[data-delete]')

for(link of links){
    
        link.addEventListener("click", function(e){
         
        e.preventDefault()

        // delete confirmation
        if(confirm("You are going to delete this image, continue ?")){
            
            //AJAX
            fetch(this.getAttribute("href"), {
                method: "DELETE",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({"_token": this.dataset.token})
            }).then(
                
                response => response.json()
                // Ajax response
            ).then(data => {
           if(data.success){
            const element = document.getElementById(this.dataset.div);
            element.remove(); 
                }         
           else
            alert(data.error)
            }).catch(e => alert(e))
        }
    })
}
}
);

