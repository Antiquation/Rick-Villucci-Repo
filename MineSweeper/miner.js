/**
 * Javascript Minesweeper game
 * 
 * Developed by: Rick Villucci
 * 
 * University of Utah Assignment
 * Class:CS4540
 * Spring 2014
 * 
 * Date Developed: Jan 2014
 * 
 * 
 */
//Clear the DOM
//reloads the DOM
$('#parentOfElementToBeRedrawn').hide().show();
// Game Variables
var multiplier; //this is changed when the mine field size button is pressed
var rows;
var cols;
var totalMines = 0;
var gameOver = false; //toggle for game functionality
var flagBtn = false;


//Start the game using Jquery find function
$(function() {
    setup();
});


//***GRAPHIC SETUP-----------------------------------------------------------


function setup() {
    //set board multiplier
    multiplier = $("#boardSetup").attr("multi");
    rows = multiplier * 5;
    cols = multiplier * 8;
    //creates the mine field table
    createGrid();
    //if flagButton is true reset flag button
    if (flagBtn) {
        //reset the button
        $("#flagButton").removeClass("flagActive");
        $("#flagButton").addClass("flagDeactive");
    }
    flagBtn = false;
    //only if previous level exists shows previous level button
    if (multiplier > 1) {
        $("#prevBtn").removeClass("nextLevel");
    }
    //flag button was pressed flags the mine
    $('#flagButton').click(function() {
        //flags the cell
        flagCell(this);
    });
    //goes forward one level
    $('input[value="Next Level"]').click(function() {
        //changes url based on the current level
        if (multiplier == 1) {
            document.location.href = "MineSweeper2.html";
        }
        if (multiplier == 2) {
            document.location.href = "MineSweeper3.html";
        }
    });
    //goes back to the main menu
    $('input[value="Back to Menu"]').click(function() {
        document.location.href = "index.html";
    });
    //goes back one level
    $('input[value="Previous Level"]').click(function() {
        //changes url based on the current level
        if (multiplier == 2) {
            document.location.href = "MineSweeper.html";
        }
        if (multiplier == 3) {
            document.location.href = "MineSweeper2.html";
        }
    })
    //when a cell is clicked this function processes the action
    $('.tableCell').click(function() {
        //send element to procees the cell
        processCell(this);
    });
}

//creates the table minefield
function createGrid() {
    var html = "<table id=\"field\">";
    //creates the rows
    for (var r = 0; r < rows; r++) {
        html += "<tr>";
        //creates the columns
        for (var c = 0; c < cols; c++) {
            //each cell is given three booleans - isClear= the cell has already been cleared of mines...
            //isMine= tells if the cell clicked has a mine... isFlagged detects if the cell has been flagged as mined
            //if the random generator returns true then the cell is given a isMine of "true"
            if (randomBool()) {
                html += "<td class=\"tableCell\" iscleared=\"false\" ismine=\"true\" isflagged=\"false\"><img class=\"cellUp\" src=\"images/cell_up.jpg\"/></td>"; //stuff contained in cells goes here
                totalMines++; //adds mines to the mineCount displayed to the user
            }
            //otherwise the isMine boolean is false
            else {
                html += "<td class=\"tableCell\" iscleared=\"false\" ismine=\"false\" isflagged=\"false\"><img class=\"cellUp\" src=\"images/cell_up.jpg\"/></td>"; //stuff contained in cells goes here
            }
        } //end for loop cols
        html += "</tr>";
    } //end for loop rows
    html += "</table>";
    //adds the table to the html produced in browser
    $("#mineTable").append(html);
    
    //*final feature setup for the function-------
    //set the wrapper spacing based on minefield size
    setWrapper();
    //dispays total mines
    displayMines();
} //end createGrid function

//displays the total number of mines to the user
function displayMines() {
    //clears number of mines
    $("#mineCount").text('');
    //adds the number of mines to text
    $("#mineCount").append("Mines: " + totalMines);
} //end displayMines function

//gets the table width info	and sets the width of the wrapper to give a good spacing
function setWrapper() {
    //if the multiplier is >1 set the wrapper width accordingly
    if (multiplier > 2) {
        var w = $('#field').width() + 100;
        $("#wrapper").css({
            "width": w
        });
    } else {
        $("#wrapper").css({
            "width": "700px"
        });
    }
} //end setWrapper function

//***LOGIC FUNCTIONS--------------------------------------------------------------------


//when called returns true or false randomly
function randomBool() {
    //generate random number between 1 and 100
    var num = Math.floor((Math.random() * 100) + 1);
    //if the number generated is multiple of 6 then true
    //this provides aprox 16% of the cells filled with mines 
    if (num % 6 == 0) {
        return true;
    }
    return false;
} //end randomBool

//changes the cursor to a flag for placing on a cell
function flagCell(element) {
    //**Graphic stuff--------------------
    //sets the wrapper so cursor will be flag for entire game
    $("#wrapper").addClass("flagCursor");
    //changes button image
    $(element).removeClass("flagDeactive");
    $(element).addClass("flagActive");
    //toggles the flagBtn boolean for future logic
    flagBtn = !flagBtn;
    
}

//detects if the flag button has been pressed and if it is it changes the img to block with flag
//if the flag is not selected then the function takes the selected flag and detects if there is a mine
//if there is it 
function processCell(element) {
    //check if the game is over
    if (!gameOver) {
        //unflags a cell if flag placed unintentionally
        if ($(element).attr("isflagged") == "true") {
            //removes the flag from the cell
            $(element).html("");
            $(element).append("<img class=\"cellUp\" src=\"images/cell_up.jpg\"/>");
            //change the "isflagged attribute to false
            $(element).attr("isflagged", "false");
            return;
        }
        //if the flag button was depressed then handle placing the flag
        if (flagBtn && totalMines > 0) {
            //changes the element in the cell to have a flag
            $(element).html("");
            $(element).append("<img class=\"placeFlag\" src=\"images/cell_w_flag.gif\"/>");
            //change the "isflagged attribute to true
            $(element).attr("isflagged", "true");
            //reset the button
            $("#flagButton").removeClass("flagActive");
            $("#flagButton").addClass("flagDeactive");
            //removes flag cursor from the wrapper
            $("#wrapper").removeClass("flagCursor");
            //reset toggle
            flagBtn = !flagBtn;
            //if the current cell being flagged is a mine
            //decriment the totalMines
            if (isMine(element)) {
                //decriment total mines
                totalMines--;
                displayMines();
            } //if mine then decriment
            //if totalMines == 0 then game won
            if (totalMines == 0) {
                //makes the text "TRY AGAIN appear and blink with land mine
                $("#instruct").text('');
                $("#instruct").append("LEVEL CLEAR...! ;)");
                $("#instruct").addClass("gameOver");
                $("#instruct").addClass("blink");
                //makes the next level button visible - previous Not applicable for the first level
                //only if next level exists
                if (multiplier < 3) {
                    $("#levelBtn").removeClass("nextLevel");
                }
            } //check if game won
        } //end process flag place
        // detect mine etc...the rest of the game logic
        else {
            if (($(element).attr("isflagged") == "false")) {
                //detects the surrounding mines and clears the empty
                if (isMine(element)) {
                    //changes the block to a land mine
                    $(element).html("");
                    $(element).append("<img id=\"landMine\" class=\"placeFlag\" src=\"images/land_mine.gif\"/>");
                    //makes the text "TRY AGAIN appear and blink with land mine
                    $("#instruct").text('');
                    $("#instruct").append("TRY AGAIN... ;)");
                    $("#instruct").addClass("gameOver");
                    $("#instruct").addClass("blink");
                    $("#instruct").css("color", "red");
                    $("#landMine").addClass("blink");
                    //makes the previous level button visible only if it exists
                    if (multiplier > 1) {
                        $("#prevBtn").removeClass("nextLevel");
                    }
                    //toggles gamestate
                    gameOver = true;
                    fagBtn = false;
                } //end if it is a mine
                //if not a mine
                else {
                    var surrounding = 0;
                    clearCell(element);
                    //**detects the column and row of the current cell clicked
                    var c = $(element).parent().children().index(element);
                    var r = $(element).parent().parent().children().index($(element).parent());
                    //**checks the cells around and talies the total mines----
                    //cell below
                    if (isMine(getCell(r + 1, c))) {
                        surrounding++;
                    }
                    //if not check for possible issolation
                    else {
                        var t = getCell(r + 1, c);
                        if (($(t).attr("iscleared") == "false") && ($(t).attr("isflagged") == "false")) {
                            clearCell(t);
                            detectClear(t);
                        }
                    }
                    //cell above
                    if (isMine(getCell(r - 1, c))) {
                        surrounding++;
                    }
                    //if not check for possible issolation
                    else {
                        var t = getCell(r - 1, c);
                        if (($(t).attr("iscleared") == "false") && ($(t).attr("isflagged") == "false")) {
                            clearCell(t);
                            detectClear(t);
                        }
                    }
                    //cell right
                    if (isMine(getCell(r, c + 1))) {
                        surrounding++;
                    }
                    //if not check for possible issolation
                    else {
                        var t = getCell(r, c + 1);
                        if (($(t).attr("iscleared") == "false") && ($(t).attr("isflagged") == "false")) {
                            clearCell(t);
                            detectClear(t);
                        }
                    }
                    //cell left
                    if (isMine(getCell(r, c - 1))) {
                        surrounding++;
                    }
                    //if not check for possible issolation
                    else {
                        var t = getCell(r, c - 1);
                        if (($(t).attr("iscleared") == "false") && ($(t).attr("isflagged") == "false")) {
                            clearCell(t);
                            detectClear(t);
                        }
                    }
                    if (surrounding > 0) {
                        $(element).append(surrounding);
                    }
                } //end is not mine
            } //end not flagged
        } //end not placing flag
    } //end gameOver check;
} //end process cell function

//clear the element cell
function clearCell(element) {
    //**clear the current cell----
    $(element).attr("iscleared", "true");
    //**changes the element in the current cell to cleared image----
    $(element).html("");
}

//mine checker checks for issolated cells based on original click
//if issolated then clears cell
//returns 
function detectClear(element) {
    var surr = 0;
    //detects the column and row of the cell passed for issolation test
    var c = $(element).parent().children().index(element);
    var r = $(element).parent().parent().children().index($(element).parent());
    //**detect the surrounding cells----
    //cell below
    if (isMine(getCell(r + 1, c))) {
        surr++;
    }
    //cell above
    if (isMine(getCell(r - 1, c))) {
        surr++;
    }
    //cell left
    if (isMine(getCell(r, c + 1))) {
        surr++;
    }
    //cell right
    if (isMine(getCell(r, c - 1))) {
        surr++;
    }
    //**if it got this far without breaking then it is issolated----
    if (surr > 0) {
        $(element).append(surr);
    }
} //end detectClear function


//gets the element at a givin row and column position
function getCell(r, c) {
    if (r >= 0 && r < rows && c >= 0 && c < cols) {
        var cell = $("#mineTable td").get(r * cols + c);
        return cell;
    }
} //end getCell

//detects whether the cell contains a mine
function isMine(element) {
    if ($(element).attr("ismine") == "true") {
        return true;
    }
    return false;
} //end isMine