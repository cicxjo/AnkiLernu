const website = window.location.protocol.concat('//').concat(window.location.host) // or location.origin
const url = website.concat('/template/word')

const buttonAddInputElt = document.getElementById('btn-add-input')
const inputsWrapperElt = document.getElementById('inputs-wrapper')
let inputId = 0

const fetchInsertNextInputElement = async () => {
  inputId++

  const data = new URLSearchParams()
  data.append('id', inputId)

  const payload = {
    method: 'POST',
    body: data
  }

  const response = await fetch(url, payload)
  const text = await response.text()
  const tmpDocument = new window.DOMParser().parseFromString(text, 'text/html')
  const inputWrapperElt = tmpDocument.getElementById('input-wrapper-'.concat(inputId))
  const buttonDeleteInputElt = tmpDocument.getElementById('btn-delete-input-'.concat(inputId))

  buttonDeleteInputElt.addEventListener('click', event => {
    inputWrapperElt.remove(inputWrapperElt)
    event.preventDefault()
  })

  inputsWrapperElt.append(inputWrapperElt)
}

buttonAddInputElt.addEventListener('click', event => {
  fetchInsertNextInputElement()
  event.preventDefault()
})
