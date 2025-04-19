document.addEventListener("DOMContentLoaded", function () {
    
    let shortcodes = document.querySelectorAll('div[data-cla-id]');

    shortcodes.forEach(function (shortcode) {
        let claId = shortcode.getAttribute('data-cla-id');
        let claViewType = shortcode.getAttribute('data-cla-view-type');
        let loadingIcon = shortcode.querySelector('img.CLA_loading-icon');
        
        let cachedArticlesUrl = cla_ajax_object.domain + '/wp-content/plugins/cached-latest-articles/cache/' + 'articles-cache'+ claId +'.json';

        loadingIcon.classList.toggle('CLA_loading-icon__hide');
        fetch(cachedArticlesUrl)
        .then(response => response.json())
        .then(data => {
            
            let output= '';

            data.forEach(function (item) {

                let date = item.post_date.split(":").slice(0, 2).join(":");

                if(claViewType === 'list'){
                    output += CLA_list_view(claId, item.post_title, item.permalink, date);
                }
                else{
                    output += CLA_Card_view(claId, item.post_title, item.post_excerpt, item.permalink, date, item.image_url);
                }
            });

            if(claViewType === 'list'){
                let ul = document.createElement('ul');
                ul.classList.add('CLACardList'+ claId);
                ul.innerHTML = output;

                shortcode.appendChild(ul);
            }
            else{
                let container = document.createElement('div');
                container.innerHTML = output;
                if (container.firstElementChild) {
                    Array.from(container.children).forEach(child => {
                        shortcode.appendChild(child);
                    });
                }
                
            }

            loadingIcon.classList.toggle('CLA_loading-icon__hide');
            
        })
        .catch(error => {
            loadingIcon.classList.toggle('CLA_loading-icon__hide');
            console.error('Error fetching shortcode:', error)
        });
         
    });


    function CLA_list_view(id, title, link, date){
        
        return `<li class="CLACardList${id}__item">
                    <a href="${link}">
                        <p>${title}</p>
                        <time class="CLACardList${id}__time">
                            ${date}           
                        </time>
                    </a>
                </li>`;

    }

    function CLA_Card_view(id, title, excerpt, link, date, imageUrl =''){
        return  `<article class="CLACard${id}">
                        <div class="CLACard${id}__image "> 
                            ${imageUrl === '' ? '' : 
                                `
                                    <a href="${link}">
                                        <figure class="CLACard${id}__figure">
                                            <img src="${imageUrl}" alt="Image" />
                                        </figure>
                                    </a>
                                `}
                        </div>

                        <div class="CLACard${id}__content ">
                            <div class="CLACard${id}__details ">   
                                <time class="CLACard${id}__time">
                                    ${date}        
                                </time>
                            </div>
                            <h3 class="CLACard${id}__title ">
                                <a href="${link}" class="CLACard${id}__link">
                                    ${title}
                                </a>
                            </h3>
                            <div class="CLACard${id}__excerpt ">
                                ${excerpt}
                            </div>
                        </div>
                    </article>`;
    }

});