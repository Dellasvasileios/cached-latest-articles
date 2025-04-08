document.addEventListener("DOMContentLoaded", function () {
    
    document.addEventListener("click", function (event) {

        // if checkbox is checked or unchecked, set the value of the hidden input to 1 or 0
        if(event.target.closest(".settingsGroup__checkbox")) {
            let checkbox = event.target.closest(".settingsGroup__checkbox").querySelector("input[type='checkbox']");
            let inputHidden = event.target.closest(".settingsGroup__checkbox").querySelector("input[type='hidden']");
            if (checkbox.checked) {
                inputHidden.value = "1";
            } else {
                inputHidden.value = "0";
            }
        }

        // if radio button is clicked, set the value of the hidden input to the value of the radio button
        if(event.target.closest(".settingsGroup__radio")) {
            let radioClickedBtn = event.target.closest(".settingsGroup__radio").querySelector(".settingsGroup__radio-view");
            let radioHiddenInput = radioClickedBtn.parentElement.parentElement.querySelector("input[name='cla_options_card[]']");
            radioHiddenInput.value = radioClickedBtn.value;
            
        }

        // if the new shortcode button is clicked then clone an existing sthortcode 
        //and pass default values to the cloned shortcode
        if(event.target.closest(".settings_newShortcode")) {
            let settingsGroup = document.querySelector(".settingsGroup").cloneNode(true);
            inputs = settingsGroup.querySelectorAll("input[type='text'], input[type='number'], input[type='radio'], input[type='hidden'], input[type='checkbox']");            
            let newIndex = findUniqueId();
            let inputIndex = settingsGroup.querySelector('.indexinput');
            inputIndex.value = newIndex;
            
            inputs.forEach(input => {
                if (input.type === "text") {
                    input.value = "";
                }
                if(input.type === 'number' && !input.classList.contains("indexinput")) {
                    input.value = "0";
                }
                if(input.type === "hidden" ) {
                    input.value = "0";
                }
                if(input.type === "checkbox") {
                    input.checked = false;
                }
                if(input.type === "radio") {
                    let sectionGroupsLength = document.querySelectorAll(".settingsGroup").length;
                    let radioGroupName = `view_type[${sectionGroupsLength + 1}]`;
                    input.setAttribute("name", radioGroupName);
                }
            });

            let codeSnippet = settingsGroup.querySelector("code");
            if(codeSnippet){
                codeSnippet.remove();
            }

            $settingsContainer = document.querySelector(".settings__inner");

            $settingsContainer.appendChild(settingsGroup);
        }

        // if the delete button is clicked then remove the shortcode from the DOM
        if(event.target.closest(".settingsGroup__delete")) {

            let sectionGroups = document.querySelectorAll(".settingsGroup");
            if (sectionGroups.length == 1) {
                alert("You can not delete the last shortcode leave it inactive.");
                return;
            }
            let settingGroup = event.target.closest(".settingsGroup");
            settingGroup.remove();
        }

    });

    //find a unique id for the new shortcode by checking the existing shortcodes ids
    function findUniqueId() {
        let maxIndex = 0;
        let indexInputs = Array.from(document.querySelectorAll(".indexinput")); 

        indexInputs = indexInputs.map(input => parseInt(input.value));

        for (let i = 1; i <= indexInputs.length; i++) {
            if (!indexInputs.includes(i)) {
                return i; 
            }
        }
        
        return indexInputs.length + 1;
    }

    // add click listners in order to move the shortcodes left or right
    document.addEventListener("click", function (event) {
        if(event.target.closest(".settingsGroup__move-left")) {
            let settingGroup = event.target.closest(".settingsGroup");
            let previousSibling = settingGroup.previousElementSibling;

            if (previousSibling) {
                settingGroup.parentNode.insertBefore(settingGroup, previousSibling);
            }
        }

        if(event.target.closest(".settingGroup__move-right")) {
            let settingGroup = event.target.closest(".settingsGroup");
            let nextElementSibling = settingGroup.nextElementSibling;
            if(nextElementSibling) {
                settingGroup.parentNode.insertBefore(nextElementSibling, settingGroup);
            }
        }
    });


});


