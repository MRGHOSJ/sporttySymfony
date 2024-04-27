$(document).ready(function() {
    $('#searchButton').click(function() {
        var keyword = $('#searchKeyword').val();
        $.ajax({
            type: 'GET',
            url: evenementSearchUrl,
            data: { keyword: keyword },
            success: function(response) {
                // Mettez à jour la partie de la page HTML affichant les événements avec les résultats de la recherche
                $('#result').html(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});
