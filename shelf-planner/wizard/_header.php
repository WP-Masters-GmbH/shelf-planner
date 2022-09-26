<style>
    a:focus {
        outline: none !important;
    }

    .wizard-placeholder {
        font-size: 16px;
        width: 95%;
        margin: auto;
        background-color: #F0F0F0;
    }

    .wizard-step-container {
        text-align: center;
        height: auto;
        position: relative;
        width: 100%;
    }

    #id-wizard-answers {
        padding: 48px;
        background-color: #FFF;
        position: relative;
        line-height: 2em;
        margin: 1% 10% 0;
        border: 1px solid #707070;
        padding-bottom: 70px;
    }

    .sphd-category {
        font-size: 36px;
        color: #000;
        opacity: 0.9;
    }

    .t1 {
        height: 48px;
        width: 48px;
        vertical-align: middle;
        text-align: center;
    }

    .t2 {
        width: 160px;
    }

    .t3 {
        position: relative;
        width: 100%;
        border-top: 3px solid #707070;

    }

    .sphd-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #874C5F;
        color: white;
        width: 47px;
        height: 47px;
        margin: 0 10px;
    }

    .sphd-step {
        background-color: #874C5F;
        border: 1px solid #707070;
        width: 71px;
        height: 71px;
        font-size: 20px;
        font-size: 36px;
        line-height: 30px;
        font-weight: 700;
    }

    .sphd-answers-1 {
        width: 60%;
    }

    .sphd-answers-1 td {
        width: 50%;
    }

    p.sphd-p {
        line-height: 3em;
        font-size: 16px;
    }

    span.sphd-p {
        font-size: 16px;
        line-height: 3em;
        margin-left: 15px;
    }

    .sphd-finish-button {
        background-color: #F37AAA !important;
        border-color: #F37AAA !important;;
        color: #FFF;
        padding: 5px 10px !important;
        font-size: 14px !important;
    }

    .sphd-final-text p {
        font-size: 16px;
    }

    body {
      font-family: 'Lato';
    }

    .steps-span {
      font-size: 24px;
      line-height: 30px;
      color: #000;
      opacity: 0.9;
    }

    .next-and-prev-btn {
      font-weight: 700;
      font-size: 24px;
      line-height: 30px;
      color: #874C5F;
    }

    .switch {
  display: inline-block;
  height: 22px;
  margin-right: 10px;
  position: relative;
  width: 49px;
}

.switch input {
  height: 0;
  opacity: 0;
  width: 0;
}

.slider.round {
  border-radius: 34px;
}

.slider, .slider:before {
  bottom: 0;
  left: 0;
  position: absolute;
  transition: .4s;
}
.slider {
  background-color: #f3f3f3;
  cursor: pointer;
  right: 0;
  top: 0;
}

.slider.round:before {
  border-radius: 50%;
}
.slider:before {
  background-color: #fff;
  content: "";
  height: 22px;
  width: 22px;
  border: 1px solid #ddd;
}

input:checked+.slider {
  background-color: #F98AB1;
}

input:checked+.slider:before {
  -webkit-transform: translateX(28px);
  transform: translateX(28px);
}

.slider:after {
  position: absolute;
  content: "";
  right: -40px;
  top: 2px;
  font-family: "Lato";
  font-weight: 400;
  font-size: 14px;
  line-height: 22px;
}

input:checked+.slider:after {
  content: "";
}

input[type=checkbox], input[type=radio] {
  border-color: #D4D4D5 !important;
}

input[type=checkbox]:focus, input[type=color]:focus, input[type=date]:focus, input[type=datetime-local]:focus, input[type=datetime]:focus, input[type=email]:focus, input[type=month]:focus, input[type=number]:focus, input[type=password]:focus, input[type=radio]:focus, input[type=search]:focus, input[type=tel]:focus, input[type=text]:focus, input[type=time]:focus, input[type=url]:focus, input[type=week]:focus, select:focus, textarea:focus {
  box-shadow: none;
  outline: none;
}

.wp-person a:focus .gravatar, a:focus, a:focus .media-icon img {
  box-shadow: none;
}

hr {
  border: none;
  border-bottom: 1px solid #A5A5A5;
  margin-bottom: 40px;
}

</style>
<div class="wizard-placeholder">
    <div class="wizard-step-container">
        <br>
        <br>
		<?php echo  esc_html( __( 'Let\'s get to know each other', QA_MAIN_DOMAIN ) ); ?>
		<?php echo  SPHD_Wizard::show_steps(); ?>
    </div>
    <div id="id-wizard-answers">