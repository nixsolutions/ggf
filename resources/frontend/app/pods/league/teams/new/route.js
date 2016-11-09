import Ember from 'ember';

export default Ember.Route.extend({
  actions: {
    closeModal() {
      this.transitionTo('league.teams');
    },
    save(team) {
      const flashMessages = Ember.get(this, 'flashMessages');
      const league = this.modelFor('league');

      team.leagueId = league.get('id');

      let newTeam = this.store.createRecord('leagueTeam', team);

      newTeam.save().then(() => {
        flashMessages.success('Team has been created');

        const  route = this.container.lookup("route:league.teams");
        route.refresh();

        this.transitionTo('league.teams');
      }).catch(() => {
        flashMessages.danger('Unable to create league');
      });

    }
  }
});
