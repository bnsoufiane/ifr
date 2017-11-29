var answers = [[]];
answers[1] = [];
answers[1][1] = "<p class='true'>True. Good ethics means you should work hard and be honest also.</p>";
answers[1][2] = "<p class='false'>False. Good ethics means you should work hard and be honest also.</p>";
answers[2] = [];
answers[2][1] = "<p class='true'>True. A company’s financial status has nothing to do with your ethics.</p>";
answers[2][2] = "<p class='false'>False. A company’s financial status has nothing to do with your ethics.</p>";
answers[3] = [];
answers[3][1] = "<p class='true'>True. Employees are paid for the time they spend on the job. When they are late often, they are stealing time.</p>";
answers[3][2] = "<p class='false'>False. Employees are paid for the time they spend on the job. When they are late often, they are stealing time.</p>";
answers[4] = [];
answers[4][1] = "<p class='true'>False. An employee who takes things from the employer is as dishonest as the person who walks in and steals.</p>";
answers[4][2] = "<p class='false'>True. An employee who takes things from the employer is as dishonest as the person who walks in and steals.</p>";
answers[5] = [];
answers[5][1] = "<p class='true'>False. Assuming that no one will care if you take company items can get you into a great deal of trouble.</p>";
answers[5][2] = "<p class='false'>True. Assuming that no one will care if you take company items can get you into a great deal of trouble.</p>";
answers[6] = [];
answers[6][1] = "<p class='false'>The correct answer is “c,” as birthday cake is to be shared as desired among employees.</p>";
answers[6][2] = "<p class='false'>The correct answer is “c,” as birthday cake is to be shared as desired among employees.</p>";
answers[6][3] = "<p class='true'>Correct.</p>";
answers[6][4] = "<p class='false'>The correct answer is “c,” as birthday cake is to be shared as desired among employees.</p>";
answers[7] = [];
answers[7][1] = "<p class='false'>The correct answer is “d,” as the other options relate to personal activities.</p>";
answers[7][2] = "<p class='false'>The correct answer is “d,” as the other options relate to personal activities.</p>";
answers[7][3] = "<p class='false'>The correct answer is “d,” as the other options relate to personal activities.</p>";
answers[7][4] = "<p class='true'>Correct.</p>";
answers[8] = [];
answers[8][1] = "<p class='false'>The correct answer is “d,” as “a” and “b” are not reasons to be unethical.</p>";
answers[8][2] = "<p class='false'>The correct answer is “d,” as “a” and “b” are not reasons to be unethical.</p>";
answers[8][3] = "<p class='false'>The correct answer is “d,” as “a” and “b” are not reasons to be unethical.</p>";
answers[8][4] = "<p class='true'>Correct.</p>";
answers[9] = [];
answers[9][1] = "<p class='false'>The correct answer is “d,” as the other three options show unethical behavior.</p>";
answers[9][2] = "<p class='false'>The correct answer is “d,” as the other three options show unethical behavior.</p>";
answers[9][3] = "<p class='false'>The correct answer is “d,” as the other three options show unethical behavior.</p>";
answers[9][4] = "<p class='true'>Correct.</p>";
answers[10] = [];
answers[10][1] = "<p class='false'>The correct answer is “c,” as the other options demonstrate unethical behavior.</p>";
answers[10][2] = "<p class='false'>The correct answer is “c,” as the other options demonstrate unethical behavior.</p>";
answers[10][3] = "<p class='true'>Correct.</p>";
answers[10][4] = "<p class='false'>The correct answer is “c,” as the other options demonstrate unethical behavior.</p>";

jQuery(document).ready(function($) {

    click_once_checkbox();
});

function click_once_checkbox() {
    $(".assessmentRadios input").change(function() {
        if (this.checked) {
            $(this).parents('li').find('input').attr('disabled', true);
            var _li_index = $(this).parents('li').index() + 1;
            var _checkbox_index = $(this).parents('fieldset').index();
            show_answers(_li_index, _checkbox_index);
        } else {
            $(this).parents('li').find('input').attr('disabled', true);
            var _li_index = $(this).parents('li').index() + 1;
            var _checkbox_index = $(this).parents('fieldset').index();
            show_answers(_li_index, _checkbox_index);
        }
    });
}

function show_answers(_li_index, _checkbox_index) {

    $(".assessmentRadios li:eq(" + (_li_index - 1) + ") fieldset:last-child").append(answers[_li_index][_checkbox_index]);


}
