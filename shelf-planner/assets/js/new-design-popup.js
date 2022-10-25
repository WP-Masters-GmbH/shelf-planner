var userImg = document.querySelectorAll('.user-img-header');
var accPopup = document.querySelectorAll('.my_acc_popup');
var userNotiflication = document.querySelectorAll('.user-notiflications');
var userNotiflicationBody = document.querySelectorAll('.notiflication-body')

for (let i = 0; i < userImg.length; i++) {
  userImg[i].addEventListener('click', (e) => {
    accPopup[i].classList.toggle('my_acc_popup_active')
  })
};

for (let j = 0; j < userNotiflication.length; j++) {
  userNotiflication[j].addEventListener('click', (e) => {
    userNotiflicationBody[j].classList.toggle('notiflication-body-active')
  })
}

/*
<?php echo "<pre>";
  print_r($categories_data);
  echo "</pre>"
?>
*/