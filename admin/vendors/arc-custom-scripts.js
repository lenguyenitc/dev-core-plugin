jQuery(document).ready(function($) {
    /***check license***/
    $('#license-btn').on('click', function(e) {
        $('#license_error').text('');
        e.preventDefault();
        var license = $('#input-license').val();
        if(license == '') return false;
        else {
            $.ajax({
                type: "post",
                url: arc_dashboard.url,
                data: {
                    'nonce' : arc_dashboard.nonce,
                    'action' : 'ARC_curl_license',
                    'license' : license,
                    'server_name': location.hostname,
                    'identification' : 'tuk_tuk',
                },
                beforeSend: function (res) {
                    console.log(res);
                    $('#license-btn svg.fa-key').remove();
                    $('#license-btn').html('<i class="fas fa-spinner fa-pulse"></i>');
                },
                success: function(res){
                    //console.log(res);
                    if(JSON.parse(res).success == 1) {
                        $.ajax({
                            type: "post",
                            url: arc_dashboard.url,
                            data: {
                                'success' : 'ok',
                                'license' : license,
                                'server_name' : location.hostname,
                                'nonce' : arc_dashboard.nonce,
                                'action' : 'ARC_check_license',
                            },
                            complete: function () {
                                messageLicense(arc_dashboard.licenseOk, 's');
                            }
                        });
                    } else if(JSON.parse(res).error == 0) {
                        messageLicense(arc_dashboard.userFalse, 'e');
                    } else {
                        messageLicense(arc_dashboard.licenseExist, 'e');
                    }
                },
                complete: function (res) {
                    console.log(res);
                }
            });
        }
    });
    function messageLicense(text, flag) {
        if(flag == 's') {
            $('#license_error').text(text).css('color', 'green');
            $('#license-btn svg.fa-key').remove();
            $('#license-btn').removeClass('btn-primary').addClass('btn-success').html('<i class="fas fa-check"></i>');
            setTimeout(function () {
                $('#license_error').slideDown(1100);
                location.reload();
            }, 1200);
        } else {
            $('#license_error').text(text).css('color', 'red');
            $('#license-btn svg.fa-key').remove();
            $('#license-btn').removeClass('btn-primary').addClass('btn-danger').html('<i class="fas fa-ban"></i>');
            setTimeout(function () {
                $('#license_error').slideDown(2500);
                $('#license-btn i.fa-ban').remove();
                $('#license-btn').removeClass('btn-danger').addClass('btn-primary').html('<i class="fas fa-key"></i> ' + arc_dashboard.activate);
            }, 1000);
        }
    } /***end check license***/


    /***check/uncheck category list***/
    sessionStorage.removeItem('all_cat_check');
    var checked = false;
    var all_check;
    $(document).on('click', '#create_cat_list', function(){
        if(false === checked) {
            $('input.check_cat').prop('checked', true);
            checked = true;
            all_check = getCheckedCheckBoxes();
            sessionStorage.setItem('all_cat_check', JSON.stringify(all_check));
        } else {
            $('input.check_cat').prop('checked', false);
            checked = false;
            sessionStorage.removeItem('all_cat_check');
        }
    });

    $(document).on('click', 'input.check_cat', function(){
        all_check = getCheckedCheckBoxes();
        sessionStorage.setItem('all_cat_check', JSON.stringify(all_check));
    });
    function getCheckedCheckBoxes() {
        var checkboxes = document.getElementsByClassName('check_cat');
        var checkboxesChecked = [];
        for (var index = 0; index < checkboxes.length; index++) {
            if (checkboxes[index].checked) {
                checkboxesChecked.push(checkboxes[index].value);
            }
        }
        return checkboxesChecked;
    }
    /**end check/uncheck category list****/
    var category_for_api = '';
    $('#full_site').on('click', function () {
        if (sessionStorage.getItem('all_cat_check') == null) {
            alert('Choose category for import!');
        } else {
            var cat = JSON.parse(sessionStorage.getItem('all_cat_check'));
            for(let key in cat) {
                category_for_api = cat + ',';
            }
            if($(this).attr('data-import') == 'done') {
                $.ajax({
                    type: "post",
                    url: arc_dashboard.url,
                    data: {
                        action: 'ARC_get_category_from_db',
                        nonce: arc_dashboard.nonce
                    },
                    beforeSend: function() {
                        $('#before_create').css('display', 'block');
                        $('#full_site').css('display', 'none');
                        $('#loader_spinner').css('display', 'block');
                    },
                    complete: function (res) {
                        //console.log(res['responseJSON']);
                        if(res['responseJSON'] == 'done') {
                            $('#before_create').css('display', 'none');
                            $.ajax({
                                type: "post",
                                url: arc_dashboard.autoImport,
                                data: {
                                    clientID: 123,
                                    category: category_for_api
                                },
                                success: function (res) {
                                    var i = 0;
                                    let keys = Object.keys(res).length;
                                    localStorage.setItem('keys', keys);
                                    for (let key in res) {
                                        if(key !== "") {
                                            $.ajax({
                                                type: "post",
                                                url: arc_dashboard.url,
                                                data: {
                                                    action: 'ARC_create_category',
                                                    nonce: arc_dashboard.nonce,
                                                    importData: res[key],
                                                    newCategory: key + ',' + $('input#' + key.replace(' ', '-') + '-num').val()
                                                },
                                                beforeSend: function() {
                                                    $('#begin_create').css('display', 'block');
                                                    $('#category_list ul').append('<li class="list-item" id="'+ key.trim().toLowerCase().replace(' ', '-') +'">' + arc_dashboard.category +' "'+ key +'" ' + arc_dashboard.createdCategory +'<i class="fa fa-spinner fa-pulse"></i></li>');
                                                },
                                                success: function(res) {
                                                    $('#category_list ul li#'+ key.trim().toLowerCase().replace(' ', '-') +' svg').removeClass('fa-spinner fa-pulse').addClass('fa-check');
                                                },
                                                complete: function() {
                                                    i++;
                                                    if((i + 1) >= localStorage.getItem('keys')) {
                                                        $('#loader_spinner').css('display', 'none');
                                                        $('#begin_create').text(arc_dashboard.autoImportDone);
                                                    }
                                                }
                                            });
                                        }
                                    }
                                }
                            });
                        }
                    }
                });
            } else {
                $.ajax({
                    type: "post",
                    url: arc_dashboard.autoImport,
                    data: {
                        clientID: 123,
                        category: category_for_api
                    },
                    success: function (res) {
                        // console.log(res);
                        var i = 0;
                        let keys = Object.keys(res).length;
                        localStorage.setItem('keys', keys);
                        /*for(let key in res) {
                            console.log(key + '-' + $('input#' + key.replace(' ', '-') + '-num').val());
                        }*/
                        for (let key in res) {
                            console.log(key + ',' + $('input#' + key.replace(' ', '-') + '-num').val());
                            if(key !== "") {
                                $.ajax({
                                    type: "post",
                                    url: arc_dashboard.url,
                                    data: {
                                        action: 'ARC_create_category',
                                        nonce: arc_dashboard.nonce,
                                        importData: res[key],
                                        newCategory: key + ',' + $('input#' + key.replace(' ', '-') + '-num').val()
                                    },
                                    beforeSend: function() {
                                        $('#full_site').css('display', 'none');
                                        $('#loader_spinner').css('display', 'block');
                                        $('#begin_create').css('display', 'block');
                                        $('#category_list ul').append('<li class="list-item" id="'+ key.trim().toLowerCase().replace(' ', '-') +'">' + arc_dashboard.category +' "'+ key +'" ' + arc_dashboard.createdCategory +'<i class="fa fa-spinner fa-pulse"></i></li>');
                                    },
                                    success: function(res) {
                                        $('#category_list ul li#'+ key.trim().toLowerCase().replace(' ', '-') +' svg').removeClass('fa-spinner fa-pulse').addClass('fa-check');
                                    },
                                    complete: function() {
                                        i++;
                                        if((i + 1) >= localStorage.getItem('keys')) {
                                            $('#loader_spinner').css('display', 'none');
                                            $('#begin_create').text(arc_dashboard.autoImportDone);
                                        }
                                    }
                                });
                            }
                        }
                    }
                });
            }
        }
    });
    /****end creating categories and import videos to site*****/

    /**get data about plugin from api****/
    var version_plugin;
    $.ajax({
        type: "post",
        url: arc_dashboard.source + 'plugin',
        data: {
            data_plugin: arc_dashboard.data_about_plugins,
            site_name: arc_dashboard.site_name
        },
        success: function (res) {
            if (res.length !== 0) {
                for (var i = 0; i < res.length; i++) {
                    $.ajax({
                        type: "post",
                        url: arc_dashboard.url,
                        data: {
                            action: 'show_data_about_plugins',
                            nonce: arc_dashboard.nonce,
                            name: res[i]['name'],
                            version: res[i]['version'],
                            desc: res[i]['description'],
                            author: res[i]['author'],
                            archive: res[i]['archive_name']
                        },
                        success: function (data) {
                            if(data['version'] == null) version_plugin = data['additional_version'];
                            else version_plugin = data['version'];
                            if(data['name'].indexOf('Core Plugin') < 0) {
                                if(data['status'] == 'active' ) {
                                    var btn = '<button class="btn btn-primary">Active</button>';
                                    var hidden = 'active-plugin';
                                    var changelog = '<a class="changelog_btn" data-log="'+data['archive_name']+'" href="#" style="display: inline-block;font-size: 14px;">Changelog</a>'
                                    $('#changelogModal div.modal-footer button').text('Latest Version Installed');
                                    if(data['new_version'] !== null) {
                                        $('#changelogModal div.modal-footer button').text('Available a new version');
                                        var update = '<button class="btn btn-info updatePlugin" data-name="'+ data['name']+ '" data-archive="'+ data['archive_name'] + '" data-version="'+ version_plugin + '">Update v.'+ data['new_version'] + '</button>';
                                    } else update = '';
                                } else if (data['status'] == 'inactive') {
                                    btn = '<a href="plugins.php" class="btn btn-secondary installPlugin" data-active="activate" data-archive="'+ data['archive_name'] + '" data-name="'+ data['name']+ '" data-version="'+ version_plugin + '">Activate</a>';
                                    hidden = 'inactive-plugin';
                                    update = '';
                                    changelog = '';
                                } else {
                                    btn = '<button class="btn btn-secondary installPlugin" data-active="" data-name="'+ data['name']+ '" data-archive="'+ data['archive_name'] + '" data-version="'+ version_plugin + '">Install</button>';
                                    hidden = 'not_install_plugin';
                                    update = '';
                                    changelog = '';
                                }
                                if(data['name'].indexOf('Player') > 0 || data['name'].indexOf('Broken') > 0) {
                                    var svg = ''; var margin = '35px';
                                } else  {
                                    svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 328.863 328.863" style="enable-background:new 0 0 328.863 328.863;" xml:space="preserve" width="25" height="25" src="https://www.easyvideosite.com/wp-content/themes/wps/img/plus-18.svg"><g id="_x34_4-18Plus_movie"><g><path class="wps-plus-18" d="M104.032,220.434V131.15H83.392V108.27h49.121v112.164H104.032z" fill="#cf48cf"></path></g><g><path class="wps-plus-18" d="M239.552,137.23c0,9.76-5.28,18.4-14.08,23.201c12.319,5.119,20,15.84,20,28.32c0,20.16-17.921,32.961-45.921,32.961    c-28.001,0-45.921-12.641-45.921-32.48c0-12.801,8.32-23.682,21.28-28.801c-9.44-5.281-15.52-14.24-15.52-24 c0-17.922,15.681-29.281,40.001-29.281C224.031,107.15,239.552,118.83,239.552,137.23z M180.51,186.352 c0,9.441,6.721,14.721,19.041,14.721c12.32,0,19.2-5.119,19.2-14.721c0-9.279-6.88-14.561-19.2-14.561 C187.23,171.791,180.51,177.072,180.51,186.352z M183.391,138.83c0,8.002,5.76,12.48,16.16,12.48c10.4,0,16.16-4.479,16.16-12.48 c0-8.318-5.76-12.959-16.16-12.959C189.15,125.871,183.391,130.512,183.391,138.83z" fill="#cf48cf"></path></g><g><path class="wps-plus-18" d="M292.864,120.932c4.735,13.975,7.137,28.592,7.137,43.5c0,74.752-60.816,135.568-135.569,135.568 S28.862,239.184,28.862,164.432c0-74.754,60.816-135.568,135.569-135.568c14.91,0,29.527,2.4,43.5,7.137V5.832 C193.817,1.963,179.24,0,164.432,0C73.765,0,0.001,73.764,0.001,164.432s73.764,164.432,164.431,164.432 S328.862,255.1,328.862,164.432c0-14.807-1.962-29.385-5.831-43.5H292.864z" fill="#cf48cf"></path></g><g><polygon class="wps-plus-18" points="284.659,44.111 284.659,12.582 261.987,12.582 261.987,44.111 230.647,44.111 230.647,66.781 261.987,66.781  261.987,98.309 284.659,98.309 284.659,66.781 316.186,66.781 316.186,44.111" fill="#cf48cf"></polygon></g></g></svg>';
                                    margin = '0px';
                                }
                                var h4class = data['name'].toLowerCase();
                                $('#plugins-container').append('<div style="max-width: 270px; width: 270px;" class="p-2 mt-lg-5 item-plugin ' + hidden + '">' +
                                    '<h4 class="product__title ' + h4class + '">' + data['name'] + '</h4>' +
                                    '<p class="svg_18" style="margin-bottom: ' + margin + '">' + svg +'</p>'+
                                    '<span class="product__version-installed">Version: ' + version_plugin + '</span>' +
                                    '<p class="product__exerpt desc">' + data['description'] + '</p>' + changelog + '<br><br>' + btn + '<br><br>' + update +
                                    '</div>'
                                );
                            } else {
                                var btn = '<button class="btn btn-primary">Active</button>';
                                var hidden = 'active-plugin';
                                var changelog = '<a class="changelog_btn" data-log="core" href="#" style="display: inline-block;font-size: 14px;">Changelog</a>'
                                var svg = ''; var margin = '35px';
                                var h4class = data['name'].toLowerCase();
                                $('#changelogModal div.modal-footer button').text('Latest Version Installed');
                                if(data['new_version'] !== null) {
                                    var update = '<button class="btn btn-info updatePlugin" data-name="'+ data['name']+ '" data-archive="'+ data['archive_name'] + '" data-version="'+ version_plugin + '">Update v.'+ data['new_version'] + '</button>';
                                    $('#changelogModal div.modal-footer button').text('Available a new version');
                                } else update = '';
                                $('#plugins-container').append('<div style="max-width: 270px; width: 270px;" class="p-2 mt-lg-5 item-plugin ' + hidden + '">' +
                                    '<h4 class="product__title ' + h4class + '" style="padding-top: 50px;">PornX Core</h4>' +
                                    '<p class="svg_18" style="margin-bottom: ' + margin + '">' + svg +'</p>'+
                                    '<span class="product__version-installed">Version: ' + version_plugin + '</span>' +
                                    '<p class="product__exerpt desc">' + data['description'] + '</p>' + changelog + '<br><br>' + btn + '<br><br>' + update +
                                    '</div>'
                                );
                                $('#name_and_version').text('PornX v.' + version_plugin);
                            }
                        }
                    });
                }
            }
        }
    });/**end get data about plugin from api****/

    /**get data about theme from api****/
    $.ajax({
        type: "post",
        url: arc_dashboard.source + 'theme',
        data: {
            data_theme: arc_dashboard.data_about_theme,
            site_name: arc_dashboard.site_name
        },
        success: function(res) {
            if(res.length !== 0) {
                var themes = res;
                /**get data about all themes for dashboard**/
                $.ajax({
                    type: "post",
                    url: arc_dashboard.url,
                    data: {
                        action: 'show_data_about_current_theme',
                        nonce: arc_dashboard.nonce,
                        themes: themes
                    },
                    success: function(data) {
                        if(data.length !== 0) {
                            for(var i = 0; i < data.length; i++) {
                                var h4class = data[i]['name'].toLowerCase();
                                if(data[i]['status'] == 'publish') {
                                    var btn = '<button class="btn btn-primary">Active</button>';
                                    var hidden = 'active-theme';
                                    var changelog = '<a class="changelog_btn" data-log="theme" href="#" style="display: inline-block;font-size: 14px;">Changelog</a>'
                                    $('#changelogModal div.modal-footer button').text('Latest Version Installed');
                                    if(data[i]['new_version'] !== null) {
                                        var update = '<button class="btn btn-info updateTheme" data-name="'+ data[i]['name']+ '" data-archive="'+ data[i]['archive_name'] + '" data-version="'+ data[i]['version'] + '">Update v.'+ data[i]['new_version'] + '</a>';
                                        $('#changelogModal div.modal-footer button').text('Available a new version');
                                    } else update = '';
                                } else if(res[i]['status'] == 'draft') {
                                    btn = '<button class="btn btn-secondary installTheme" data-active="activate" data-name="'+ data[i]['name']+ '" data-archive="'+ data[i]['archive_name'] + '" data-version="'+ data[i]['version'] + '">Activate</button>';
                                    hidden = 'inactive-theme';
                                    update = '';
                                    changelog = '';
                                } else {
                                    btn = '<button class="btn btn-secondary installTheme" data-active="" data-name="'+ data[i]['name']+ '" data-archive="'+ data[i]['archive_name'] + '" data-version="'+ data[i]['version'] + '">Install</button>';
                                    hidden = 'not_install_theme';
                                    update = '';
                                    changelog = '';
                                }
                                $('#theme-container').append('<div style="max-width: 270px; width: 270px;" class="p-2 mt-lg-5 item-theme ' + hidden + '">' +
                                    '<h4 class="product__title ' + h4class + '">' + data[i]['name'] + '</h4>' +
                                    '<span class="product__version-installed">Version: ' + data[i]['version'] + '</span>' +
                                    '<p class="product__exerpt desc">' + data[i]['description'] + '</p>' +
                                    changelog + '<br><br>' + btn + '<br><br>' + update +
                                    '</div>'
                                );
                            }
                        }
                    }
                });
            }
        }
    });/**end get data about theme from api****/

    /******install/upgrade/activate theme or plugin*****/
    $(document).on('click', '.installPlugin', function(){
        var this_btn = $(this);
        $(this).addClass('now_installed');
        $('button.installPlugin').not('.now_installed').remove();
        $('a.installPlugin').not('.now_installed').remove();
        $('button.updatePlugin').remove();
        $('button.installTheme').remove();
        $('button.updateTheme').remove();
        var data_name = $(this).attr('data-name');
        var data_archive = $(this).attr('data-archive');
        var data_version = $(this).attr('data-version');
        var data_active = $(this).attr('data-active');
        if(data_active !== "activate" && data_active !== "active") {
            $.ajax({
                type: "post",
                url: arc_dashboard.url,
                data: {
                    action: 'vicetemple_install_product',
                    nonce: arc_dashboard.nonce,
                    method: 'install',
                    product_type: 'plugin', //eg. theme/plugin
                    product_sku: data_archive, //name theme/plugin eg. dev-core-plugin
                    product_zip: arc_dashboard.source + 'zipDownload/' + data_archive +'.zip', //name zip archive theme/plugin eg. dev-core-plugin.zip
                    product_slug: data_archive, //slug theme/plugin eg. dev-core-plugin
                    product_folder_slug: data_archive, //folder slug theme/plugin eg. dev-core-plugin
                    new_version: data_version, //version theme/plugin
                },
                beforeSend: function() {
                  this_btn.text(arc_dashboard.installingBegan);
                },
                complete: function (res) {
                    if(res.responseText === "true") {
                        $.ajax({
                            type: "post",
                            url: arc_dashboard.url,
                            data: {
                                action: 'check_is_plugin_install',
                                nonce: arc_dashboard.nonce,
                                plugin: data_archive + '/' + data_archive + '.php'
                            },
                            success: function (res) {
                                if(res == 'not_active') {
                                    this_btn.text(arc_dashboard.installingDone).attr('data-active', 'activate');
                                    setTimeout(function () {
                                        location.reload();
                                    }, 300);
                                }
                            }
                        });
                    }
                }
            });
        }
        if(data_active === "activate" && data_active !== "active") {
            /*$.ajax({
                type: "post",
                url: arc_dashboard.url,
                data: {
                    action: 'activate_new_plugin',
                    nonce: arc_dashboard.nonce,
                    plugin: data_archive + '/' + data_archive + '.php'
                },
                complete: function (res) {
                    if(res.responseJSON === "active") {
                        this_btn.removeClass('btn-secondary').removeClass('installPlugin').addClass('btn-primary').text('Active').attr('data-active', 'active');
                        setTimeout(function () {
                            location.reload();
                        }, 300);
                    }
                }
            });*/
        }
    });

    $(document).on('click','.updatePlugin',function() {
        var this_btn = $(this);
        $(this).addClass('now_updated');
        $('button.installPlugin').not('.now_updated').remove();
        $('a.installPlugin').not('.now_installed').remove();
        $('button.updatePlugin').not('.now_updated').remove();
        $('button.installTheme').remove();
        $('button.updateTheme').remove();
        var data_name = $(this).attr('data-name');
        var data_archive = $(this).attr('data-archive');
        var data_version = $(this).attr('data-version');
        $.ajax({
            type: "post",
            url: arc_dashboard.url,
            data: {
                action: 'vicetemple_install_product',
                nonce: arc_dashboard.nonce,
                method: 'upgrade',
                product_type: 'plugin',
                product_sku: data_archive,
                product_zip: arc_dashboard.source + 'zipDownload/' + data_archive + '.zip',
                product_slug: data_archive,
                product_folder_slug: data_archive,
                new_version: data_version,
            },
            beforeSend: function() {
                this_btn.text(arc_dashboard.upgradingBegan);
            },
            complete: function (res) {
                if(res.responseText === "true") {
                    this_btn.text(arc_dashboard.upgradingDone);
                    setTimeout(function () {
                        this_btn.css('display', 'none');
                    }, 700);
                    setTimeout(function () {
                        location.reload();
                    }, 300);
                }
            }
        });
    });

    $(document).on('click', '.installTheme', function(){
        var this_btn = $(this);
        var data_name = $(this).attr('data-name');
        var data_archive = $(this).attr('data-archive');
        var data_version = $(this).attr('data-version');
        var data_active = $(this).attr('data-active');
        if(data_active !== "activate" && data_active !== "active") {
            $.ajax({
                type: "post",
                url: arc_dashboard.url,
                data: {
                    action: 'vicetemple_install_product',
                    nonce: arc_dashboard.nonce,
                    method: 'install',
                    product_type: 'theme',
                    product_sku: data_archive,
                    product_zip: arc_dashboard.source + 'zipDownload/' + data_archive + '.zip',
                    product_slug: data_archive,
                    product_folder_slug: data_archive,
                    new_version: data_version,
                },
                beforeSend: function () {
                    this_btn.text(arc_dashboard.installingBegan);
                },
                complete: function (res) {
                    if (res.responseText === "true") {
                        this_btn.text(arc_dashboard.activate).attr('data-active', 'activate');
                    }
                }
            });
        }
        if(data_active === "activate" && data_active !== "active") {
            $.ajax({
                type: "post",
                url: arc_dashboard.url,
                data: {
                    action: 'check_is_theme_install',
                    nonce: arc_dashboard.nonce,
                    slug: data_archive,
                    name: data_name,
                },
                complete: function (res) {
                    this_btn.removeClass('btn-secondary').removeClass('installTheme').addClass('btn-primary').text('Active').attr('data-active', 'active');
                    setTimeout(function () {
                        location.reload();
                    }, 300);
                }
            });
        }
    });

    $(document).on('click', '.updateTheme', function(){
        var this_btn = $(this);
        $(this).addClass('now_updated');
        $('button.updateTheme').not('.now_updated').remove();
        $('button.installPlugin').not('.now_installed').remove();
        $('a.installPlugin').not('.now_installed').remove();
        $('button.updatePlugin').remove();
        $('button.installTheme').remove();
        var data_name = $(this).attr('data-name');
        var data_archive = $(this).attr('data-archive');
        var data_version = $(this).attr('data-version');
        $.ajax({
            type: "post",
            url: arc_dashboard.url,
            data: {
                action: 'vicetemple_install_product',
                nonce: arc_dashboard.nonce,
                method: 'upgrade',
                product_type: 'theme',
                product_sku: data_archive,
                product_zip: arc_dashboard.source + 'zipDownload/' + data_archive + '.zip',
                product_slug: data_archive,
                product_folder_slug: data_archive,
                new_version: data_version,
            },
            beforeSend: function() {
                this_btn.text(arc_dashboard.upgradingBegan);
            },
            complete: function (res) {
                if(res.responseText === "true") {
                    this_btn.text(arc_dashboard.upgradingDone);
                    setTimeout(function () {
                        this_btn.css('display', 'none');
                    }, 700);
                    setTimeout(function () {
                        location.reload();
                    }, 300);
                }
            }
        });
    });/******end install/upgrade/activate theme or plugin*****/


    /**get data about active plugin for log tab**/
    $.ajax({
        type: "post",
        url: arc_dashboard.url,
        data: {
            action: 'load_data_about_plugin',
            nonce: arc_dashboard.nonce,
        },
        success: function(res) {
			//console.log(res);
            if(res !== null) {
				if(res['plugins'].length == 1) {
					$('#pluginList').css('display','none');
				} else {
					for(var i = 0; i < res['plugins'].length; i++) {
					    if(res['plugins'][i] == 'Dev Core Plugin') continue;
						$('select#product').append('<option value="' + res['plugins'][i] + '">' + res['plugins'][i] + '</option>');
					}
				}                
            } else {
				$('#pluginList').css('display','none');
			}
        }
    });/**end get data about active plugin for log tab**/


    /**get data for copy logs from log table**/
    $.ajax({
        type: "post",
        url: arc_dashboard.url,
        data: {
            action: 'get_data_from_log_table',
            nonce: arc_dashboard.nonce,
        },
        success: function(res) {
            $('#copy_area').text(JSON.stringify(res['logs']));
        }
    });
    /**end get data for copy logs from log table**/

    /**delete data from log table**/
    $('#delete_logs').on('click', function() {
        $.ajax({
            type: "post",
            url: arc_dashboard.url,
            data: {
                action: 'delete_data_from_log_table',
                nonce: arc_dashboard.nonce,
            }
        });
        $('tr.logs_row').remove();
    });
    /**end delete data from log table**/

    /****filter products*****/
    $('#select_theme').on('change', function () {
        var option = $('#select_theme option:selected').val();
        var item = $('#theme-container');
        if(option == 'activeTh') {
            item.find('div.inactive-theme').addClass('hidden-product')
            item.find('div.not_install_theme').addClass('hidden-product');
            item.find('div.not_install_theme').addClass('hidden-product');
            item.find('div.active-theme').removeClass('hidden-product');
        }
        if(option == 'inactiveTh') {
            item.find('div.active-theme').addClass('hidden-product');
            item.find('div.not_install_theme').addClass('hidden-product');
            item.find('div.inactive-theme').removeClass('hidden-product');
        }
        if(option == 'not_install_theme') {
            item.find('div.not_install_theme').removeClass('hidden-product');
            item.find('div.inactive-theme').addClass('hidden-product')
            item.find('div.active-theme').addClass('hidden-product');
        }
        if(option == 'allTh') {
            item.find('.item-theme').removeClass('hidden-product');
        }
    });
    $('#select_plugin').on('change', function () {
        var option = $('#select_plugin option:selected').val();
        var item = $('#plugins-container');
        if(option == 'activePl') {
            item.find('div.inactive-plugin').addClass('hidden-product')
            item.find('div.not_install_plugin').addClass('hidden-product');
            item.find('div.not_install_plugin').addClass('hidden-product');
            item.find('div.active-plugin').removeClass('hidden-product');
        }
        if(option == 'inactivePl') {
            item.find('div.active-plugin').addClass('hidden-product');
            item.find('div.not_install_plugin').addClass('hidden-product');
            item.find('div.inactive-plugin').removeClass('hidden-product');
        }
        if(option == 'not_install_plugin') {
            item.find('div.not_install_plugin').removeClass('hidden-product');
            item.find('div.inactive-plugin').addClass('hidden-product')
            item.find('div.active-plugin').addClass('hidden-product');
        }
        if(option == 'allPl') {
            item.find('.item-plugin').removeClass('hidden-product');
        }
    });/****end filter products*****/

    /**filter data from selects type and product**/
    $('select').on('change', function() {
        var product = $('#product option:selected').val();
        var type = $('#type option:selected').val();
        if(product == 'all' && type == 'all') {
            $('.filter_row').remove();
            $('.logs_row').show(100);
        } else {
            $.ajax({
                type: "post",
                url: arc_dashboard.url,
                data: {
                    action: 'select_filter_data',
                    nonce: arc_dashboard.nonce,
                    product: product,
                    type: type
                },
                success: function (res) {
                    $('.filter_row').remove();
                    $('.logs_row').hide(100);
                    for(var i = 0; i < res['logs'].length; i++) {
                        if(res['logs'][i]['type'] == 'success') badge = 'success';
                        if(res['logs'][i]['type'] == 'notice') badge = 'info';
                        if(res['logs'][i]['type'] == 'warning') badge = 'warning';
                        if(res['logs'][i]['type'] == 'error') badge = 'danger';
                        $('.logs_table tbody').append('<tr class="filter_row">' +
                            '<td>' +
                            '<small>' + res['logs'][i]['date'] + '</small></td>' +
                            '<td>' +
                            '<span class="badge badge-' + badge + '">' + res['logs'][i]['type'] + '</span></td>' +
                            '<td>' +
                            '<small>' + res['logs'][i]['product'] + '</small></td>' +
                            '<td>' +
                            '<small>' + res['logs'][i]['message'] + '</small></td>' +
                            '<td>' +
                            '<small>' + res['logs'][i]['location'] + '</small></td>' +
                            '</tr>');
                    }
                }
            });
        }
    });/**end filter data from selects type and product**/

    /*****filter data from message and location inputs*****/
    $('.filter_text').on('input',function () {
        var id = $(this).attr('id');
        var text = $(this).val();
        if($(this).val() == '') {
            $('.filter_row').remove();
            $('.logs_row').show(100);
        } else {
            $.ajax({
                type: "post",
                url: arc_dashboard.url,
                data: {
                    action: 'message_filter_data',
                    nonce: arc_dashboard.nonce,
                    text: text,
                    id: id
                },
                success: function (res) {
                    $('.filter_row').remove();
                    $('.logs_row').hide(100);
                    for(var i = 0; i < res['logs'].length; i++) {
                        if(res['logs'][i]['type'] == 'success') badge = 'success';
                        if(res['logs'][i]['type'] == 'notice') badge = 'info';
                        if(res['logs'][i]['type'] == 'warning') badge = 'warning';
                        if(res['logs'][i]['type'] == 'error') badge = 'danger';
                        $('.logs_table tbody').append('<tr class="filter_row">' +
                            '<td>' +
                            '<small>' + res['logs'][i]['date'] + '</small></td>' +
                            '<td>' +
                            '<span class="badge badge-' + badge + '">' + res['logs'][i]['type'] + '</span></td>' +
                            '<td>' +
                            '<small>' + res['logs'][i]['product'] + '</small></td>' +
                            '<td>' +
                            '<small>' + res['logs'][i]['message'] + '</small></td>' +
                            '<td>' +
                            '<small>' + res['logs'][i]['location'] + '</small></td>' +
                            '</tr>');
                    }
                }
            });
        }
    });/***** end filter data from message and location inputs*****/

/*    $('a.nav-link').on('click', function () {
       var tab = $(this).attr('href');
       if(tab == '#likedis') {
           //console.log(tab);
       }
    });*/
    /**filter likes**/
    var sort;
    $('select.likesDisSelect').on('change', function() {
        var name = $(this).attr('name');
        if(name == 'logsLike') {
            name = 'likes_count';
            sort = $('select[name="logsLike"] option:selected').val();
        }
        else {
            name = 'dislikes_count';
            sort = $('select[name="logsDislike"] option:selected').val();
        }
        if(sort == 'all') {
            $('.filter_likes_row').remove();
            $('.likesDis_row').show(100);
        } else {
            $.ajax({
                type: "post",
                url: arc_dashboard.url,
                data: {
                    action: 'filter_likes',
                    nonce: arc_dashboard.nonce,
                    sort: sort,
                    name: name
                },
                success: function (res) {
                    $('.filter_likes_row').remove();
                    $('.likesDis_row').hide(100);
                    for(var i = 0; i < res.length; i++) {
                        $('.likedis_table tbody').append('<tr data-id="' + res[i]['id'] + '" class="filter_likes_row">' +
                            '<td>' +
                            '<p style="font-size: 17px"><a style="color: black;" href="'  + res[i]['guid'] + '">' + res[i]['post_title']  + '</a></p>' +
                            '<ul style="display: inline-flex">' +
                            '<li style="margin-right: 10px;">' +
                            '<a class="editP" style="font-size: 14px; text-decoration: none" href="post.php?action=edit&post=' + res[i]['id'] + '" target="_blank"><i class="fa fa-edit"></i> Edit post</a></li>' +
                            '<li class="draftP" style="color: #007bff; margin-right: 10px; font-size: 14px; cursor: pointer;" data-draft="' + res[i]['id'] + '"><i class="fa fa-file-archive"></i> Draft</li>' +
                            '<li class="deleteP" style="color: #007bff; margin-right: 10px; font-size: 14px; cursor: pointer;" data-delete="' + res[i]['id'] + '"><i class="fa fa-trash"></i> Delete</li></ul></td>' +
                            '<td>' +
                            '<p><img style="width: 200px" src="' + res[i]['thumb'] + '" /></p></td>' +
                            '<td>' +
                            '<p>' + res[i]['likes'] + '</p></td>' +
                            '<td>' +
                            '<p>' + res[i]['dislikes'] + '</p></td>' +
                            '</tr>');
                    }
                }
            });
        }
    });/**end filter data from selects type and product**/
    $(document).on('click', 'li.draftP', function () {
        var postID = $(this).attr('data-draft');
        $.ajax({
            type: "post",
            url: arc_dashboard.url,
            data: {
                action: 'draft_dislikePost',
                nonce: arc_dashboard.nonce,
                postID: postID
            },
            success: function(res) {
                //console.log(res);
                $('.likedis_table tbody tr[data-id="' + res + '"]').remove();
            }
        });
    });
    $(document).on('click', 'li.deleteP', function () {
        var postID = $(this).attr('data-delete');
        $.ajax({
            type: "post",
            url: arc_dashboard.url,
            data: {
                action: 'delete_dislikePost',
                nonce: arc_dashboard.nonce,
                postID: postID
            },
            success: function(res) {
                //console.log(res);
                $('.likedis_table tbody tr[data-id="' + res + '"]').remove();
            }
        });
    });

    /***filter support messages by type***/
    $('select#msgType').on('change', function () {
        var type = $('select#msgType option:selected').val();
        if(type == 'all') {
            $('.filter_support_row').remove();
            $('.support_row').show(100);
        } else {
            $.ajax({
                type: "post",
                url: arc_dashboard.url,
                data: {
                    action: 'filter_supportMsg',
                    nonce: arc_dashboard.nonce,
                    type: type,
                },
                success: function (res) {
                    $('.filter_support_row').remove();
                    $('.support_row').hide(100);
                    for(var i = 0; i < res['msg'].length; i++) {
                        $('.support_table tbody').append('<tr data-id="' + res['msg'][i]['id'] + '" class="filter_support_row">' +
                            '<td>' +
                            '<small>' + res['msg'][i]['date'] + '</small></td>' +
                            '<td>' +
                            '<span class="badge badge-info">' + res['msg'][i]['type'] + '</span></td>' +
                            '<td>' +
                            '<small>' + res['msg'][i]['title'] + '</small></td>' +
                            '<td>' +
                            '<small>' + res['msg'][i]['msg'] + '</small></td>' +
                            '<td>' +
                            '<small>' + res['msg'][i]['email'] + '</small></td>' +
                            '<td>' +
                            '<small>' + res['msg'][i]['name'] + '</small></td>' +
                            '</tr>');
                    }
                }
            });
        }
    });
    /***delete all massages from support table***/
    $('#delete_all_msg').on('click', function () {
        $.ajax({
            type: "post",
            url: arc_dashboard.url,
            data: {
                action: 'delete_data_from_support_table',
                nonce: arc_dashboard.nonce,
            },
            success: function () {
                $('.filter_support_row').remove();
                $('.support_row').remove();
            }
        });
    });/*** end delete all massages from support table***/

    /****add IPs to ban****/
    $('#add_to_ban').on('click', function(){
        var list_ip = $('#list_ip').val();
        add_to_ban(list_ip);
        $('#list_ip').val('');
    });
    function add_to_ban(list_ip){
        var jqXHR = $.post(arc_dashboard.url, {
                        action  : 'add_to_ban',
                        nonce : arc_dashboard.nonce,
                        list_ip : list_ip,
                    });
                    jqXHR.done(function(response){
                        for (var key in response) {
                            var deleted_points = response[key].replace(/[\.\:\/]/g, '');
                            $('table.ban_table tbody').append('<tr class="' + deleted_points + ' tr_for_del">' +
                                '<td><span>' + response[key] + '</span></td>' +
                                '<td><button type="button" class="button button-primary delete_from_ban" id="' + response[key] + '">Delete</button></td></tr>');
                        }
                    });
                    jqXHR.fail(function (response) {
                    });
    }/****end add IPs to ban****/

    /******delete current IP from ban****/
    $(document).on('click', '.delete_from_ban', function(){
        var ip_for_delete = ($(this).attr('id'));
        delete_to_ban(ip_for_delete);
    });
    function delete_to_ban(ip_for_delete){
        var jqXHR = $.post(arc_dashboard.url, {
                        action  : 'delete_from_ban',
                        nonce : arc_dashboard.nonce,
                        ip_for_delete : ip_for_delete,
                    });
                    jqXHR.done(function(response){
                        $("tr" + "." + response).remove();
                    });
                    jqXHR.fail(function (response) {
                    });
    }/******end delete current IP from ban****/

    /*****delete all IPs from ban*****/
    $(document).on('click', '#delete_all_ip_from_ban', function(){
        delete_all_ip_from_ban();
    });
    function delete_all_ip_from_ban(){
        var jqXHR = $.post(arc_dashboard.url, {
                        action  : 'delete_all_ip_from_ban',
                        nonce : arc_dashboard.nonce,
                    });
                    jqXHR.done(function(response){
                        if(response){
                            $("tr.tr_for_del").remove();
                        }
                    });
                    jqXHR.fail(function (response) {
                    });
    }/*****end delete all IPs from ban*****/


    /***filter reports by type***/
    $('select#reportType').on('change', function () {
        var type = $('select#reportType option:selected').val();
        if(type == 'all') {
            $('.filter_report_row').remove();
            $('.report_row').show(100);
        } else {
            $.ajax({
                type: "post",
                url: arc_dashboard.url,
                data: {
                    action: 'filter_reportMsg',
                    nonce: arc_dashboard.nonce,
                    type: type,
                },
                success: function (res) {
                    //console.log(res);
                    $('.filter_report_row').remove();
                    $('.report_row').hide(100);
                    for(var i = 0; i < res.length; i++) {
                        if(res[i]['postId'].indexOf('&user') < 0) {
                            $('.reports_table tbody').append('<tr data-id="' + res[i]['id'] + '" data-post="' + res[i]['postId'] + '" class="filter_report_row">' +
                                '<td>' +
                                '<small>' + res[i]['date'] + '</small></td>' +
                                '<td>' +
                                '<span class="badge badge-' + res[i]['badge'] + '">' + res[i]['textBadge'] + '</span></td>' +
                                '<td>' +
                                '<small>' + res[i]['msg'] + '</small></td>' +
                                '<td>' +
                                '<small>' + res[i]['title'] + '</small> ' +
                                '<ul style="display: inline-flex">' +
                                '<li style="margin-right: 10px;">' +
                                '<a class="editPR" style="font-size: 14px; text-decoration: none" href="post.php?action=edit&post='+ res[i]['postId'] + '" target="_blank"><i class="fa fa-edit"></i> Edit</a></li>' +
                                '<li class="draftPR" style="color: #007bff; margin-right: 10px; font-size: 14px; cursor: pointer;" data-draft="'+ res[i]['postId'] + '"><i class="fa fa-file-archive"></i> Draft</li>' +
                                '<li class="deletePR" style="color: #007bff; margin-right: 10px; font-size: 14px; cursor: pointer;" data-delete="'+ res[i]['postId'] + '"><i class="fa fa-trash"></i> Delete</li></ul>' +
                                '</td>' +
                                '<td>' +
                                '<button style="float: left" type="button" class="close deleteReport" data-delete="' + res[i]['id'] + '">' +
                                '<span style="font-size: 24px; color: red;" aria-hidden="true">&times;</span>' +
                                '</button>' +
                                '</tr>');
                        } else {
                            $('.reports_table tbody').append('<tr data-id="' + res[i]['id'] + '" data-post="' + res[i]['postId'] + '" class="filter_report_row">' +
                                '<td>' +
                                '<small>' + res[i]['date'] + '</small></td>' +
                                '<td>' +
                                '<span class="badge badge-' + res[i]['badge'] + '">' + res[i]['textBadge'] + '</span></td>' +
                                '<td>' +
                                '<small>' + res[i]['msg'] + '</small></td>' +
                                '<td><a target="_blank" href="/public-profile/?xxx='+res[i]['title'].replace('&user','')+'">' +
                                '<small>' + res[i]['title'] + '</small></a>' +
                                '</td>' +
                                '<td>' +
                                '<button style="float: left" type="button" class="close deleteReport" data-delete="' + res[i]['id'] + '">' +
                                '<span style="font-size: 24px; color: red;" aria-hidden="true">&times;</span>' +
                                '</button>' +
                                '</tr>');
                        }
                    }
                }
            });
        }
    });/*** end filter reports by type***/

    /***delete all reports from report table***/
    $('#delete_all_reports').on('click', function () {
        $.ajax({
            type: "post",
            url: arc_dashboard.url,
            data: {
                action: 'delete_all_reports',
                nonce: arc_dashboard.nonce,
            },
            success: function () {
                $('.filter_report_row').remove();
                $('.report_row').remove();
            }
        });
    });/*** end delete all massages from support table***/

    /**** draft and delete posts on report table****/
    $(document).on('click', 'li.draftPR', function () {
        var postID = $(this).attr('data-draft');
        $.ajax({
            type: "post",
            url: arc_dashboard.url,
            data: {
                action: 'draft_dislikePost',
                nonce: arc_dashboard.nonce,
                postID: postID,
                report: 'report'
            },
            success: function(res) {
                //console.log(res);
                $('.reports_table tbody tr[data-post="' + res + '"]').remove();
            }
        });
    });
    $(document).on('click', 'li.deletePR', function () {
        var postID = $(this).attr('data-delete');
        $.ajax({
            type: "post",
            url: arc_dashboard.url,
            data: {
                action: 'delete_dislikePost',
                nonce: arc_dashboard.nonce,
                postID: postID,
                report: 'report'
            },
            success: function(res) {
                //console.log(res);
                $('.reports_table tbody tr[data-post="' + res + '"]').remove();
            }
        });
    });
    /**** end draft and delete posts on report table****/

    /****delete one report from table****/
    $(document).on('click', 'button.deleteReport', function () {
        var reportID = $(this).attr('data-delete');
        $.ajax({
            type: "post",
            url: arc_dashboard.url,
            data: {
                action: 'delete_one_report',
                nonce: arc_dashboard.nonce,
                reportID: reportID,
            },
            success: function(res) {
                $('.reports_table tbody tr[data-id="' + res + '"]').remove();
            }
        });
    });/**** end delete one report from table****/
});