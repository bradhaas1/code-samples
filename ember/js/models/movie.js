MovieTracker.Movie = Ember.Object.extend({
	id: null,
	title: null,
	watched: false,
	rating: 0,
	//titleAndRating: function(){
	//	return this.get("title") + " has a rating of " + this.get("rating")
	//}.property()

	titleAndRating: function() {
		return this.get('title') + ' has a rating of ' + this.get('rating');
	}.property()

	});

		MovieTracker.ActionMovie = MovieTracker.Movie.extend({
			genre: 'action'
		});