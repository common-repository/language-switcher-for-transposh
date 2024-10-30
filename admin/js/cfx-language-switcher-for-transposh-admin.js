(function ($) {
	"use strict";
	$(function () {
		/**
		 * define these variables as global since codemirror is initialized within toggleCustomCssEditor() function and editor and editorSettings must be available in load_style() function too
		 */
		var editor, editorSettings;

		function showSpinner() {
			$(".myspinner").css({
				visibility: "visible",
				opacity: "1",
			});
		}

		function hidseSpinner() {
			$(".myspinner").css({
				visibility: "hidden",
				opacity: "0",
			});
		}

		function load_style() {
			var stylesheet = $("#select_style").val();
			showSpinner();
			var data = {
				action: "load_style",
				stylesheet: stylesheet,
			};
			$.post(ajaxurl, data, function (result) {
				if ("" !== result) {
					if (editor) {
						editor.codemirror.toTextArea();
						$("#code_editor_page_css").val(result);
						editor = wp.codeEditor.initialize(
							$("#code_editor_page_css"),
							editorSettings
						);
						$(".CodeMirror-wrap").addClass("disabled");
					}
				}
			}).done(function () {
				hidseSpinner();
			});
		}

		function setSelectStylesOPtions() {
			if ($("#select_style").val() !== "none") {
				$('#select_style option:contains("Choose one")').text("Clear");
			} else {
				$('#select_style option:contains("Clear")').text("Choose one");
			}
		}

		$("#select_style").on("change", function () {
			if ($("#select_style").val() === "none") {
				setSelectStylesOPtions();
				hidseSpinner();
				if (editor) {
					editor.codemirror.toTextArea();
					$("#code_editor_page_css").val("");
					editor = wp.codeEditor.initialize(
						$("#code_editor_page_css"),
						editorSettings
					);
					$(".CodeMirror-wrap").addClass("disabled");
				}
			} else {
				setSelectStylesOPtions();
				load_style();
			}
		});

		$("#automode-toggler").on("click", function () {
			toggleAutomode();
		});

		function toggleAutomode() {
			if ($("#automode-toggler").is(":checked")) {
				$("#menus").show();
				$("#switcher-type").show();
				$("#switcher-classes").show();
			} else {
				$("#menus").hide();
				$("#switcher-type").hide();
				$("#switcher-classes").hide();
			}
		}

		$("#customCSS").on("click", function () {
			$("#select_style").val("none");
			setSelectStylesOPtions();
			toggleCustomCssEditor();
		});

		function toggleCustomCssEditor() {
			if ($("#customCSS").is(":checked")) {
				$("#custom_css_editor").show();
				editorSettings = wp.codeEditor.defaultSettings
					? _.clone(wp.codeEditor.defaultSettings)
					: {};
				editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
					autoRefresh: true,
					indentUnit: 4,
					tabSize: 4,
					mode: "css",
				});
				editor = wp.codeEditor.initialize(
					$("#code_editor_page_css"),
					editorSettings
				);
				$(".CodeMirror-wrap").addClass("disabled");
			} else {
				$("#custom_css_editor").hide();
			}
		}

		$("#clear-codemirror").on("click", function (e) {
			e.preventDefault();
			$("#select_style").val("none");
			$("#select_style").trigger("change");
		});

		$(document).on("change", "#switcher_type", function () {
			if ($(this).val() == "list") {
				if (!$("#flag_styles").hasClass("hidden")) {
					$("#flag_styles").addClass("hidden");
				}
				$("#dropdown_styles").removeClass("hidden");
			} else {
				$("#flag_styles").removeClass("hidden");
				if (!$("#dropdown_styles").hasClass("hidden")) {
					$("#dropdown_styles").addClass("hidden");
				}
			}
		});

		// $("nav.nav-tab-wrapper.lsft a").on("click", function (e) {
		// 	e.preventDefault();
		// 	$("nav.nav-tab-wrapper.lsft .nav-tab").removeClass("active");
		// 	$(".tab-panel").removeClass("active");
		// 	$(this).addClass("active");
		// 	var target = $(this).data("target");
		// 	$("#" + target).addClass("active");
		// 	$("#activeTab").val(target);
		// });

		// function setActivePanel() {
		// 	$("nav.nav-tab-wrapper.lsft .nav-tab.active").trigger("click");
		// }

		function toggleListItems() {
			if ($("#switcher_type").val() === "list") {
				$("#dropdown_styles").removeClass("hidden");
			} else {
				$("#dropdown_styles").addClass("hidden");
			}
		}

		function showConfirm() {
			$("#confirmMessage").fadeIn("slow");
			setInterval(() => {
				$("#confirmMessage").fadeOut("slow");
			}, 4000);
		}

		function copyToClipboard(element) {
			// var $temp = $("<input>");
			// $("body").append($temp);
			// $temp.val($(element).val()).select();
			// document.execCommand("copy");
			// $temp.remove();
			// showConfirm();
			navigator.clipboard.writeText(element).then(function () {
				alert('It worked! Do a CTRL - V to paste')
			}, function () {
				alert('Failure to copy. Check permissions for clipboard')
			});
		}

		$("#copy-to-clipboard").on("click", function (e) {
			e.preventDefault();
			copyToClipboard($("#code_editor_page_css").val());
			// $(".textarea").text().select();
			// document.execCommand("copy");
		});

		toggleAutomode();
		toggleCustomCssEditor();
		toggleListItems();
		load_style();
		setSelectStylesOPtions();
		$("#select_style").trigger("change");
	}); //end jQuery
})(jQuery);
