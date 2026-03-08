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

})
