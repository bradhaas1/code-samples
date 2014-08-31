var MovieTracker = Ember.Application.create();

window.onload = showMovie;

function showMovie() {
	//var actionMovie = MovieTracker.Movie.create({
	//	title: "The Action Movie"
	//});


	var actionMovie = MovieTracker.ActionMovie.create({
		title: "An Action Movie",
		rating: 4
	});
	alert(actionMovie.title);
	var txt = actionMovie.titleAndRating;
	alert(txt);
}

//App = Ember.Application.create();

//App.Router.map(function() {
//  // put your routes here
//});

//App.IndexRoute = Ember.Route.extend({
//  model: function() {
//    return ['red', 'yellow', 'blue'];
//  }
//});
