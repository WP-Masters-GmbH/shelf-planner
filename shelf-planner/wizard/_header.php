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
        box-shadow: -1px 1px 8px 0px rgba(34, 60, 80, 0.2);
    }

    .sphd-category {
        font-size: 36px;
        color: #486A82;
    }

    .t1 {
        height: 48px;
        width: 48px;
        vertical-align: middle;
        text-align: center;
    }

    .t2 {
        width: 100px;
    }

    .t3 {
        position: relative;
        width: 100%;
        border: 1px solid #575757;

    }

    .sphd-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #575757;
        color: white;
        width: 32px;
        height: 32px;
        margin: 0 auto;
    }

    .sphd-step {
        background-color: #486A82;
        width: 48px;
        height: 48px;
        font-size: 20px;
        margin-left: 10px;
        margin-right: 10px;
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
</style>
<div class="wizard-placeholder">
    <div class="wizard-step-container">
        <br>
        <br>
		<?php echo  esc_html( __( 'Let\'s get to know each other', QA_MAIN_DOMAIN ) ); ?>
		<?php echo  SPHD_Wizard::show_steps(); ?>
    </div>
    <div id="id-wizard-answers">