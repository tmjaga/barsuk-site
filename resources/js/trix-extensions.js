document.addEventListener("trix-before-initialize", () => {

    Trix.config.textAttributes.color = {
        styleProperty: "color",
        inheritable: true
    }

    Trix.config.textAttributes.background = {
        styleProperty: "background-color",
        inheritable: true
    }

    Trix.config.textAttributes.fontSize = {
        styleProperty: "font-size",
        inheritable: true
    }

})


document.addEventListener("trix-initialize", function (event) {

    const toolbar = event.target.toolbarElement
    const editor = event.target.editor

    const group = document.createElement("span")
    group.className = "trix-button-group"

    group.innerHTML = `
        <input type="color" id="trix-text-color" title="Text color">
        <input type="color" id="trix-bg-color" title="Background">
        <select id="trix-font-size">
            <option value="">Size</option>
            <option value="12px">Small</option>
            <option value="16px">Normal</option>
            <option value="20px">Large</option>
            <option value="28px">Huge</option>
        </select>
    `

    toolbar.querySelector(".trix-button-row").appendChild(group)

    group.querySelector("#trix-text-color").addEventListener("input", (e) => {
        editor.activateAttribute("color", e.target.value)
    })

    group.querySelector("#trix-bg-color").addEventListener("input", (e) => {
        editor.activateAttribute("background", e.target.value)
    })

    group.querySelector("#trix-font-size").addEventListener("change", (e) => {
        if (!e.target.value) return
        editor.activateAttribute("fontSize", e.target.value)
    })

    const clearButton = document.createElement("button")
    clearButton.type = "button";
    clearButton.className = "trix-button";
    clearButton.title = "Clear formatting";
    clearButton.id = "clear-formatting";
    clearButton.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
      <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
    </svg>
    `;

    group.appendChild(clearButton);

    clearButton.addEventListener("click", () => {
        editor.deactivateAttribute("bold")
        editor.deactivateAttribute("italic")
        editor.deactivateAttribute("strike")

        editor.deactivateAttribute("color")
        editor.deactivateAttribute("background")
        editor.deactivateAttribute("fontSize")
    });
});
