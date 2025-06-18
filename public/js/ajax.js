/*
	AJAX
	@Author Yves Ponchelet
	@Version 0.1
	@Creation date: 05/09/2023
	@Last update: 12/03/2025
*/

$(document).ready(function() {
    // Mettre à jour le statut d'un contenu
    $('.changer-statut').click(function(event) {
        // Empêcher le comportement par défaut du lien (pour éviter le rechargement de la page)
        event.preventDefault();

        var parametre = $(this).data('content');

        $.ajax({
            type: 'POST',
            url: '/Kodio/private/ajax/changeStatus', 
            data: { id: parametre }, // Envoyez le paramètre au serveur
            dataType: "json",
            success: function(response) {
                if (response.status) {
                   location.reload();
                } 
                else {
                    alert(response.message);
                }
            },
            error: function(error) {
                alert(error);
            }
        });
    });

    $('#addMenu-form').submit(function(event) {
        // Empêcher le comportement par défaut du formulaire (pour éviter le rechargement de la page)
        event.preventDefault();

        var formData = {
            parent: $('select[name=parentMenu]').val(),
            label: $('input[name=label]').val(),
            language: $('select[name=language]').val(),
            content: $('select[name=content]').val(),
            ordre: $('input[name=ordre]').val()
        };

        $.ajax({
            type: 'POST',
            url: '/Kodio/private/ajax/addMenu', 
            data: formData, // Envoyez les paramètres au serveur
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    location.reload();
                } 
                else {
                    alert(response.message);
                }
            },
            error: function(error) {
                alert(error);
            }
        });
    });

    // Ajouter une langue
    $('#addLanguage-form').submit(function(event) {
        // Empêcher le comportement par défaut du formulaire (pour éviter le rechargement de la page)
        event.preventDefault();

        var formData = {
            language: $('input[name=language]').val(),
            key: $('input[name=language-key]').val()
        };

        $.ajax({
            type: 'POST',
            url: '/Kodio/private/ajax/addLanguage', 
            data: formData, // Envoyez les paramètres au serveur
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    location.reload();
                } 
                else {
                    alert(response.message);
                }
            },
            error: function(error) {
                alert(error);
            }
        });
    });

    // Supprimer une langue
    $('.delete-language').click(function(event) {
        // Empêcher le comportement par défaut du lien (pour éviter le rechargement de la page)
        event.preventDefault();

        var parametre = $(this).data('key');

        $.ajax({
            type: 'POST',
            url: '/Kodio/private/ajax/deleteLanguage', 
            data: { key: parametre }, // Envoyez le paramètre au serveur
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    location.reload();
                } 
                else {
                    alert(response.message);
                }
            },
            error: function(error) {
                alert(error);
            }
        });
    });

    $('#addRole-form').submit(function(event) {
        // Empêcher le comportement par défaut du formulaire (pour éviter le rechargement de la page)
        event.preventDefault();

        var formData = {
            role: $('input[name=role]').val(),
            key: $('input[name=role-key]').val()
        };

        $.ajax({
            type: 'POST',
            url: '/Kodio/private/ajax/addRole', 
            data: formData, // Envoyez les paramètres au serveur
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    location.reload();
                } 
                else {
                    alert(response.message);
                }
            },
            error: function(error) {
                alert(error);
            }
        });
    });

    // Supprimer une langue
    $('.delete-role').click(function(event) {
        // Empêcher le comportement par défaut du lien (pour éviter le rechargement de la page)
        event.preventDefault();

        var parametre = $(this).data('key');

        $.ajax({
            type: 'POST',
            url: '/Kodio/private/ajax/deleteRole', 
            data: { key: parametre }, // Envoyez le paramètre au serveur
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    location.reload();
                } 
                else {
                    alert(response.message);
                }
            },
            error: function(error) {
                alert(error);
            }
        });
    });

    $('#addCategory-form').submit(function(event) {
        // Empêcher le comportement par défaut du formulaire (pour éviter le rechargement de la page)
        event.preventDefault();

        var formData = {
            cat: $('input[name=cat]').val(),
            key: $('input[name=cat-key]').val()
        };

        $.ajax({
            type: 'POST',
            url: '/Kodio/private/ajax/addCategory', 
            data: formData, // Envoyez les paramètres au serveur
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    location.reload();
                } 
                else {
                    alert(response.message);
                }
            },
            error: function(error) {
                alert(error);
            }
        });
    });

    // Supprimer une langue
    $('.delete-category').click(function(event) {
        // Empêcher le comportement par défaut du lien (pour éviter le rechargement de la page)
        event.preventDefault();

        var parametre = $(this).data('key');

        $.ajax({
            type: 'POST',
            url: '/Kodio/private/ajax/deleteCategory', 
            data: { key: parametre }, // Envoyez le paramètre au serveur
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    location.reload();
                } 
                else {
                    alert(response.message);
                }
            },
            error: function(error) {
                alert(error);
            }
        });
    });

	// Ajouter un utilisateur
	$('#addUser-form').submit(function(event) {
        // Empêcher le comportement par défaut du formulaire (pour éviter le rechargement de la page)
        event.preventDefault();

        var formData = {
            nickname: $('input[name=nickname]').val(),
            password: $('input[name=password]').val(),
            email: $('input[name=email]').val(),
            role: $('select[name=addUser-role]').val()
        };

        $.ajax({
            type: 'POST',
            url: '/Kodio/private/ajax/addUser', 
            data: formData, // Envoyez les paramètres au serveur
            dataType: "json",
            success: function(response) {
                if (response.status) {
		            location.reload();
		        } 
		        else {
		            alert(response.message);
		        }
            },
            error: function(error) {
                alert(error);
            }
        });
    });

    // Changer le rôle d'un utilisateur
	$('#newRole').submit(function(event) {
        // Empêcher le comportement par défaut du formulaire (pour éviter le rechargement de la page)
        event.preventDefault();

        var formData = {
            id: $('input[name=user-id]').val(),
            role: $('select[name=updatedRole]').val()
        };

        $.ajax({
            type: 'POST',
            url: '/Kodio/private/ajax/newRole', 
            data: formData, // Envoyez les paramètres au serveur
            dataType: "json",
            success: function(response) {
                if (response.status) {
		            location.reload();
		        } 
		        else {
		            alert(response.message);
		        }
            },
            error: function(error) {
                alert(error);
            }
        });
    });

	// Regénérer un mot de passe
    $('.new-Password').click(function(event) {
        // Empêcher le comportement par défaut du lien (pour éviter le rechargement de la page)
        event.preventDefault();

        var parametre = $(this).data('parametre');

        $.ajax({
            type: 'POST',
            url: '/Kodio/private/ajax/newPassword', 
            data: { id: parametre }, // Envoyez le paramètre au serveur
            dataType: "json",
            success: function(response) {
                if (response.status) {
					var alert = document.getElementById('alert-text');

					alert.textContent = response.message;

		            $("#alert-success").addClass('show');

					var delay = 5000; // 5 secondes

					setTimeout(function() {
					    $("#alert-success").removeClass('show');
					}, delay);

		        } 
		        else {
		            alert(response.message);
		        }
            },
            error: function(error) {
                alert(error);
            }
        });
    });

    // Ajouter une étiquette
    $('#addTag-form').submit(function(event) {
        // Empêcher le comportement par défaut du formulaire (pour éviter le rechargement de la page)
        event.preventDefault();

        var formData = {
            label: $('input[name=label]').val(),
            color: $('input[name=color]').val(),
        };

        $.ajax({
            type: 'POST',
            url: '/Kodio/private/ajax/addTag', 
            data: formData, // Envoyez les paramètres au serveur
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    location.reload();
                } 
                else {
                    alert(response.message);
                }
            },
            error: function(error) {
                alert(error);
            }
        });
    });
});
