const website = location.protocol.concat('//').concat(location.host); // or location.origin
const url = website.concat('/field/word');

const buttonAddInputElt = document.getElementById('btn-add-input');
const inputsWrapperElt = document.getElementById('inputs-wrapper');
let inputId = 0;

const fetchInsertNextInputElement = async () => {
  const data = new URLSearchParams()
  data.append('id', inputId++);

  const payload = {
    method: 'POST',
    body: data,
  };

  const response = await fetch(url, payload);
  const text = await response.text();
  const parser = new DOMParser();
  const tmpDocument = parser.parseFromString(text, 'text/html');
  const inputWrapperElt = tmpDocument.getElementsByClassName('input-wrapper')[0];
  inputsWrapperElt.append(inputWrapperElt);
};

buttonAddInputElt.addEventListener('click', event => {
  fetchInsertNextInputElement();
  event.preventDefault();
});
