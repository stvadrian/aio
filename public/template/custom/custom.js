function sweetAlert(type, msg) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    Toast.fire({
        icon: type,
        title: msg
    })
}

function alertMessage(title, confirmText, denyText, onConfirmAction) {
    Swal.fire({
        title: title,
        showDenyButton: true,
        confirmButtonText: confirmText,
        denyButtonText: denyText,
    }).then((result) => {
        if (result.isConfirmed) {
            if (typeof onConfirmAction === 'function') {
                onConfirmAction();
            }
        } else if (result.isDenied) {
            Swal.fire('Cancelled!', '', 'warning')
        }
    })
}


function popupwindow(url, title) {
    var left = (screen.width / 2) - (800 / 2);
    var top = (screen.height / 2) - (800 / 2);
    return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=800, height=800, top=' + top + ', left=' + left);
}


function validateBSStepper(stepper) {
    var currentStep = stepper._currentIndex + 1;
    var totalSteps = stepper._steps.length;
    var $currentStepFields = $("#stepper" + currentStep).find(".form-control:not(.optional), .form-check-input:not(.optional), .select2, .select2multiple");

    var emptyFields = $currentStepFields.filter(function () {
        if ($(this).is(":radio") || $(this).is(":checkbox")) {
            return !$('[name="' + $(this).attr("name") + '"]').is(":checked");
        } else if ($(this).is(".select2")) {
            return $(this).val() === null;
        } else if ($(this).is(".select2multiple")) {
            return $(this).val().length == 0;
        } else {
            return !$(this).val();
        }
    });

    if (emptyFields.length > 0) {
        emptyFields.addClass("is-invalid");
        $(".select2.is-invalid + .select2-container .select2-selection, .select2multiple.is-invalid + .select2-container .select2-selection").addClass("is-invalid");
        sweetAlert("error", "Field Cannot be Empty!");
        setTimeout(() => {
            emptyFields.first().focus();
        }, 50);
    } else {
        if (currentStep == totalSteps) {
            let form = stepper._element
            $('.custom-date').each(function () {
                let curVal = $(this).val();
                let convertedVal = formatDate('en', curVal, 'Y-DD-M')
                $(this).val(convertedVal)
            })
            $('input[type="datetime-local"]').each(function () {
                let curVal = $(this).val();
                let convertedVal = curVal.replace("T", " ");
                let id = $(this).attr('id');
                $('#' + id + '-hidden').val(convertedVal)
                $(this).attr('disabled', true);
            })
            form.submit();
        } else {
            stepper.next();
        }
    }
}

function customDate(elid) {
    var el = document.getElementById(elid);

    function checkValue(str, max) {
        if (str.charAt(0) !== '0' || str == '00') {
            var num = parseInt(str);
            if (isNaN(num) || num <= 0 || num > max) num = 1;
            str = num > parseInt(max.toString().charAt(0)) && num.toString().length == 1 ? '0' + num : num.toString();
        };
        return str;
    };

    el.addEventListener('input', function (e) {
        this.type = 'text';
        var input = this.value;
        if (/\D\/$/.test(input)) input = input.substr(0, input.length - 3);
        var values = input.split('/').map(function (v) {
            return v.replace(/\D/g, '')
        });
        if (values[0]) values[0] = checkValue(values[0], 31);
        if (values[1]) values[1] = checkValue(values[1], 12);
        var output = values.map(function (v, i) {
            return v.length == 2 && i < 2 ? v + ' / ' : v;
        });
        this.value = output.join('').substr(0, 14);
    });

    el.addEventListener('blur', function (e) {
        this.type = 'text';
        var input = this.value;
        var values = input.split('/').map(function (v, i) {
            return v.replace(/\D/g, '')
        });
        var output = '';

        if (values.length == 3) {
            var year = values[2].length !== 4 ? values[2] = "0000" : parseInt(values[2]);
            var month = parseInt(values[1]) - 1;
            var day = parseInt(values[0]);
            var d = new Date(year, month, day);
            if (!isNaN(d)) {
                el.innerText = d.toString();
                var dates = [d.getDate(), d.getMonth() + 1, d.getFullYear()];
                output = dates.map(function (v) {
                    v = v.toString();
                    return v.length == 1 ? '0' + v : v;
                }).join(' / ');
            };
        };
        this.value = output;
    });
}

function validatePassword(pass) {
    let pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
    let isValid = pass.match(pattern) !== null
    if (isValid) {
        return true;
    }
    return false;
}

function copyToClipboard(value) {
    var sourceText = value
    var tempInput = $('<input>');
    $('body').append(tempInput);

    tempInput.val(sourceText).select();
    document.execCommand('copy');
    tempInput.remove();

    sweetAlert('success', 'Copied to clipboard!');
}

function formatDate(lang, date, format) {
    moment.locale(lang);
    var formatted_date = moment(date).format(format);
    return formatted_date;
}

function scrollToTop(amount) {
    $('html, body').animate({ scrollTop: amount }, 500);
}

function generateChart(chartId, chartData) {
    var ctx = $(chartId)[0].getContext('2d');

    var datasets = [];

    chartData.datasets.forEach(function (dataset) {
        datasets.push({
            label: dataset.label,
            data: dataset.data,
            backgroundColor: dataset.backgroundColor,
            borderColor: dataset.borderColor,
            borderWidth: dataset.borderWidth
        });
    });

    var myChart = new Chart(ctx, {
        type: chartData.chartType,
        data: {
            labels: chartData.labels,
            datasets: datasets, // Dynamically created datasets
        },
        options: chartData.chartOptions
    });
}


$(document).ready(function () {

    $('.logout-btn').click(function () {
        $('#logout_form').submit();
    });

    $('.isnumber').on('input', function () {
        let inputValue = $(this).val();
        let numericValue = inputValue.replace(/[^0-9.,]/g, '');
        $(this).val(numericValue);
    })

    $('.isalpha').on('input', function (event) {
        let inputValue = $(this).val();
        let alphaValue = inputValue.replace(/[^a-z A-Z]/g, '');
        $(this).val(alphaValue);
    });

    $('.nospaces').keydown(function (event) {
        var charCode = event.keyCode;
        if (charCode == 32)
            return false;
    });

    $('.modal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    });

    $('.show-loader').click(function () {
        $('.loader').show();
    });

    var curdate = moment().locale('id').format('dddd, D MMMM yyyy');
    $('#curdate_formatted').html(curdate);
    setInterval(() => $('#clock').html((new Date().toLocaleTimeString()), 1000));

    $('#password.enhanced').focus(function () {
        $('#password_requirements').removeClass('d-none');
    }).blur(function () {
        $('#password_requirements').addClass('d-none');
    }).keyup(function () {
        // Validate lowercase letters
        var lowerCaseLetters = /[a-z]/g;
        if ($(this).val().match(lowerCaseLetters)) {
            $('#pw_lower').removeClass('text-danger').addClass('text-success');
        } else {
            $('#pw_lower').removeClass('text-success').addClass('text-danger');
        }

        // Validate capital letters
        var upperCaseLetters = /[A-Z]/g;
        if ($(this).val().match(upperCaseLetters)) {
            $('#pw_upper').removeClass('text-danger').addClass('text-success');
        } else {
            $('#pw_upper').removeClass('text-success').addClass('text-danger');
        }

        // Validate numbers
        var numbers = /[0-9]/g;
        if ($(this).val().match(numbers)) {
            $('#pw_number').removeClass('text-danger').addClass('text-success');
        } else {
            $('#pw_number').removeClass('text-success').addClass('text-danger');
        }

        // Validate length
        if ($(this).val().length >= 6) {
            $('#pw_length').removeClass('text-danger').addClass('text-success');
        } else {
            $('#pw_length').removeClass('text-success').addClass('text-danger');
        }
    });

    $('.custom-date').each(function () {
        customDate($(this).attr('id'));
    });

    $('.js-validate-form').each(function () {
        $(this).submit(function (e) {
            if (!$(this).data("submitted")) {
                e.preventDefault();
                var form = $(this);
                var $fields = form.find(".form-control:not(.optional):not(.note-form-control), .form-check-input:not(.optional), .select2, .select2multiple");

                var emptyFields = $fields.filter(function () {
                    if ($(this).is(":radio") || $(this).is(":checkbox")) {
                        return !$('[name="' + $(this).attr("name") + '"]').is(":checked");
                    } else if ($(this).is(".select2")) {
                        return $(this).val() == null;
                    } else if ($(this).is(".select2multiple")) {
                        return $(this).val().length == 0;
                    } else {
                        return !$(this).val();
                    }
                });

                if (emptyFields.length > 0) {
                    emptyFields.addClass("is-invalid");
                    $(".select2.is-invalid + .select2-container .select2-selection, .select2multiple.is-invalid + .select2-container .select2-selection").addClass("is-invalid");
                    sweetAlert("error", "Field Cannot be Empty!");
                    setTimeout(() => {
                        emptyFields.first().focus();
                    }, 50);
                } else {
                    form.unbind().submit();
                }
            }
        })
    })

    $('.select2').each(function () {
        let parentModal = $(this).data('parent');
        let dataPlaceholder = $(this).data('placeholder');
        if (parentModal != '') {
            $(this).select2({
                placeholder: dataPlaceholder,
                dropdownParent: $('#' + parentModal)
            })
        } else {
            $(this).select2({
                placeholder: dataPlaceholder,
            })
        }
    })
    $('.select2multiple').each(function () {
        let parentModal = $(this).data('parent');
        let dataPlaceholder = $(this).data('placeholder');
        if (parentModal != '') {
            $(this).select2({
                multiple: true,
                placeholder: dataPlaceholder,
                dropdownParent: $('#' + parentModal)
            })
        } else {
            $(this).select2({
                multiple: true,
                placeholder: dataPlaceholder,
            })
        }
    });
    $('.btn-copy').click(function () {
        let val = $(this).data('copy');
        copyToClipboard(val);
    })
    $('.btn-popup').click(function () {
        let link = $(this).data('link');
        let title = $(this).data('title');
        popupwindow(link, title);
    });
    $('.btn-delete').each(function () {
        $(this).click(function (e) {
            let item = $(this).data('name');
            alertMessage(
                'Are you sure want to delete <b>' + item + '</b>?',
                'Yes',
                'No',
                () => {
                    $(this).parent().submit();
                },
            )
        })
    })
    $('.upload-file').change(function () {
        $(this).closest('form').submit();
    })



    $('.loader').hide();


    $('.summernote').each(function () {
        let placeHolder = $(this).data('placeholder');
        $(this).summernote({
            placeholder: placeHolder,
            height: 150,
            disableDragAndDrop: true,
            codeviewFilter: false,
            codeviewIframeFilter: true,
            help: false,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['font', ['superscript', 'subscript']],
                ['para', ['ol']],
            ],
        });
    });


    //// REMOVING INVALID CLASS
    $('.form-control').on('input', function () {
        $(this).removeClass('is-invalid');
    });
    $('.form-select').change(function () {
        $(this).removeClass('is-invalid');
    });
    $(".select2, .select2multiple").on("change", function () {
        var $container = $(this).next().closest(".select2-container");
        $container.find(".select2-selection").removeClass("is-invalid");
    });
    $(".form-check-input").on("change", function () {
        var name = $(this).attr("name");
        $('input[name="' + name + '"]').removeClass("is-invalid");
    });



    ////// DATATABLE HELPER
    $('.js-dataTable').each(function () {
        let dom = $(this).data('dom');
        if (dom) {
            $(this).DataTable({
                "dom": dom
            });
        } else {
            $(this).DataTable({
                "paging": true,
                "responsive": true,
                "info": true,
                "lengthChange": false,
                "autoWidth": true,
            });
        }
    });
    $('.js-dataTable-buttons').each(function () {
        $(this).DataTable({
            "paging": true,
            "responsive": true,
            "info": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "excel", "pdf", "print"]
        }).buttons().container().appendTo('.col-md-6:eq(0)');
    });
    $('.js-dataTable-sort').each(function () {
        let columnIndex = $(this).data('sort-index');
        let direction = $(this).data('sort-direction');
        $(this).DataTable({
            "paging": true,
            "responsive": true,
            "info": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [columnIndex, direction],
        });
    });


    /////// SEARCH MENU 
    $('#search_menu').on('input', function (e) {
        var searchText = $(this).val().toLowerCase();

        $("#sidebarnav li.sidebar-item").each(function () {
            var menuItemText = $(this).find(".hide-menu").text().toLowerCase();
            var menuItem = $(this);

            if (menuItemText.indexOf(searchText) === -1) {
                menuItem.hide();
            } else {
                menuItem.show();
            }
        });
        $("#sidebarnav li.nav-small-cap").each(function () {
            var header = $(this);
            var visibleItems = header.nextUntil("li.nav-small-cap").filter(":visible");
            if (visibleItems.length === 0) {
                header.hide();
            } else {
                header.show();
            }
        });
    });
});


