const buttonAddInputElt = document.getElementById('btn-add-input');
const inputsWrapperElt = document.getElementById('inputs-wrapper');
let inputId = 0;

const fetchInsertNextInputElement = async () => {
  const url = 'http://ankilernu.fr/field/word';

  const data = new URLSearchParams()
  data.append('id', inputId++);

  const payload = {
    method: 'POST',
    body: data,
  };

  await fetch(url, payload)
    .then(response => { return response.text(); })
    .then(text => {
      const parser = new DOMParser();
      const tmpDocument = parser.parseFromString(text, 'text/html');
      const inputWrapperElt = tmpDocument.getElementsByClassName('input-wrapper')[0];
      inputsWrapperElt.append(inputWrapperElt);
    });
  };

buttonAddInputElt.addEventListener('click', event => {
  fetchInsertNextInputElement();
  event.preventDefault();
});
