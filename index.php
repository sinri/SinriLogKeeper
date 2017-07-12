<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>SinriLogKeeper</title>
    <script src="./assets/jquery-2.2.4.min.js"></script>
    <script src="./assets/se_loading.js"></script>
    <link rel="stylesheet" type="text/css" href="./assets/se_loading.css">
    <script src="./assets/slk.js"></script>
    <link rel="stylesheet" type="text/css" href="./assets/slk.css">
    <script type="text/javascript">
        var display_data = {};
        var se_loading_instance = null;
        var filter_method_mapping = {
            pure_grep: {
                title: "Grep",
                readme: "Use Shell GREP command (-n)"
            },
            pure_grep_case_insensitive: {
                title: "Grep Case Insensitive",
                readme: "Use Shell GREP command, case insensitively (-n -i)"
            },
            egrep: {
                title: "EGrep",
                readme: "Use Shell GREP command with extended regular expression support (-n -E)"
            },
            text: {
                title: "Match Text Case Sensitive",
                readme: "Use PHP to filter file content"
            },
            text_case_insensitive: {
                title: "Match Text Case Insensitive",
                readme: "Use PHP to filter file content, case insensitively"
            },
            regex: {
                title: "PHP-Style Regular Expression",
                readme: "Use PHP to filter file content, using regular expression"
            }
        };
        $(document).ready(function () {
            se_loading_instance = se_loading('loading_div');
            loadFiles();
            initializePage();
            writeFilterMethodSelect();

            initFileSearcher();
        });
        function writeFilterMethodSelect() {
            var code = "";
            for (var key in filter_method_mapping) {
                code += '<option value="' + key + '">' + filter_method_mapping[key]['title'] + '</option>';
            }
            $("#filter_method_select").html(code);
            $("#filter_method_select").on('change', refreshFilterMethodReadme);
            refreshFilterMethodReadme();
        }
        function refreshFilterMethodReadme() {
            var key = $("#filter_method_select").val();
            console.log('refreshFilterMethodReadme for ' + key)
            $("#filter_method_readme").html(filter_method_mapping[key]['readme']);
        }

        var search_keyword_timeout = null;

        function initFileSearcher() {
            $("#file_select_search").on('keyup', function () {
                if (search_keyword_timeout) {
                    window.clearTimeout(search_keyword_timeout);
                }
                var search_keyword = $("#file_select_search").val();
                search_keyword_timeout = window.setTimeout(searchInFileSelect, 1000);
            });
        }
        function searchInFileSelect() {
            var search_keyword = $("#file_select_search").val();
            var current_selection = $("#file_select").val();
            // console.log(search_keyword);

            // console.log($("#file_select option"));
            $("#file_select option").css('display', 'block');
            if (search_keyword) {
                var k = $("#file_select option").filter(function (mono_index, mono) {
                    var r = mono.value.search(search_keyword);
                    // console.log('filter '+search_keyword,mono_index,mono,r);
                    return (r < 0);
                });
                // console.log(k);
                for (var j = 0; j < k.length; j++) {
                    if (k[j].value == current_selection) {
                        $("#file_select").val('');
                    }
                    k[j].style.display = 'none';
                }
            }

            /*
             // The first method to do this
             let option_group_list=$("#file_select").children('optgroup');
             for(let i=0;i<option_group_list.length;i++){
             let option_group=option_group_list[i].children;
             for(let j=0;j<option_group.length;j++){
             let option=option_group[j];
             if(option.value.search(search_keyword)>=0){
             option.style.display='block';
             }else{
             if(option.value==current_selection){
             $("#file_select").val('');
             }
             option.style.display='none';
             }
             }
             }
             */
        }
    </script>
</head>
<body>
<h1>
    SinriLogKeeper for <span id="display_data_header"></span>
</h1>

<blockquote id="motto" style="display: none">
    For God shall bring every work into judgment,
    with every secret thing,
    whether it be good,
    or whether it be evil.
    (Ecclesiastes 12:14 KJV)
</blockquote>
<div id="controller_pane">
    <div class="condition_row" id="user_auth_row">
        <div class='row_label'>Username</div>
        <input type="text" id="username">
        <div class='row_label'>Password</div>
        <input type="password" id="password">
    </div>
    <div class="condition_row">
        <div class='row_label'>File</div>
        <input id="file_select_search" placeholder="Regex to filter files">
        <select id="file_select">
            <option value='null'>Not Loaded Yet</option>
        </select>
        &nbsp;
        <button class="extension" onclick='download_this_file()'>↓</button>
    </div>
    <div class="condition_row">
        <div class='row_label'>Line Range</div>
        <input type='text' id="line_begin">
        To
        <input type='text' id="line_end">
        &nbsp;
        <button onclick="rangeTool_firstLines(1000)">Head</button>
        <button onclick="rangeTool_lastLines(1000)">Tail</button>
        <button onclick="rangeTool_prevLines(1000)">Previous</button>
        <button onclick="rangeTool_nextLines(1000)">Next</button>
        For 1000 Lines
    </div>
    <div class="condition_row">
        <div class='row_label'>Around Lines</div>
        <input type="text" id="around_lines" value="10">
        <!--
        Quick Ranger Setter
        <a href="javascript:void(0);" onclick="rangeTool_firstLines(1000)">First 1000 Lines</a>
        <a href="javascript:void(0);" onclick="rangeTool_lastLines(1000)">Last 1000 Lines</a>
        <a href="javascript:void(0);" onclick="rangeTool_prevLines(1000)">Previous 1000 Lines</a>
        <a href="javascript:void(0);" onclick="rangeTool_nextLines(1000)">Next 1000 Lines</a>
        -->
    </div>
    <div class="condition_row">
        <div class='row_label'>Filter Method</div>
        <select id="filter_method_select">
        </select>
        <span id="filter_method_readme"></span>
    </div>
    <div class="condition_row">
        <div class='row_label'>Filter</div>
        <input type="text" id="filter_text">
        &nbsp;
        <button class="primary" onclick="close_alert();searchWithFilter()">SEARCH</button>
    </div>
    <div class="condition_row">
        With Limitation of Result Line Count of <span id="maxResultLineCount"></span>.
    </div>
</div>
<div id="alert_pane">
    <p></p>
    <div style="text-align:right;margin-right: 10px">
        <button onclick="close_alert()">Close This Alert</button>
    </div>
</div>
<div id="news_pane"></div>
<div id="display_pane">
</div>
<div id="loading_div"></div>
<div id="footer_div">
    <a href="http://github.everstray.com/SinriLogKeeper/">Version 1.4 For Leqee</a>
    <span id="display_data_footer_use_auth"></span>
    |
    Copyright 2016 Sinri Edogawa
    |
    <a href="https://raw.githubusercontent.com/sinri/SinriLogKeeper/master/LICENSE">License GPLv2</a>
    <br>
    Note: SinriLogKeeper uses
    <a href="http://www.ecma-international.org/ecma-262/6.0/#sec-let-and-const-declarations">ES6</a>,
    please use Chrome 41, Edge 12, Firefox 44, IE 11, Opera 17, Safari 10, or later versions of each.
</div>
<div id="ad_div" style="text-align:center">
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <ins class="adsbygoogle"
         style="display:inline-block;width:728px;height:90px"
         data-ad-client="ca-pub-5323203756742073"
         data-ad-slot="1082241140"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</div>
</body>
</html>
