(function() {
  var App, AppView, Officer, OfficerList, OfficerView, Officers;
  Officer = Backbone.Model.extend({
    validate: function(attrs) {},
    defaults: function() {},
    clear: function() {
      return this.destroy();
    }
  });
  OfficerList = Backbone.Collection.extend({
    model: Officer,
    url: "/officers"
  });
  OfficerView = Backbone.View.extend({
    tagName: "tr",
    template: _.template("<td><%- id %></td>\n<td><%- name %></td>\n<td><%- title %></td>\n<td><%- User.email %></td>\n<td>\n  <div class=\"not-user-<%- User.id %>\">\n    <% if (role == 3 && !isSuperAdmin) { %>\n      This officer is a super-admin.\n    <% } else { %>\n      <select class=\"user_role_select\">\n        <option value=\"0\" <% if(role == 0){ %>selected <% } %>>Program Officer</option>\n        <option value=\"1\" <% if(role == 1){ %>selected <% } %>>Contracting Officer</option>\n        <option value=\"2\" <% if(role == 2){ %>selected <% } %>>Admin</option>\n        <% if (isSuperAdmin) { %>\n          <option value=\"3\" <% if(role == 3){ %>selected <% } %>>Super Admin</option>\n        <% } %>\n      </select>\n    <% } %>\n  </div>\n  <div class=\"only-user only-user-<%- User.id %>\">\n    You're a <%- role_text %>.\n  </div>\n</td>\n<td>\n  <% if (role != 3){ %>\n    <div class=\"super-admin-only\">\n      <div class=\"not-user-<%- User.id %>\">\n        <% if (!User.banned_at){ %>\n          <a class=\"btn btn-danger ban-button btn-mini\">Ban Officer</a>\n        <% } else { %>\n          <a class=\"btn unban-button btn-mini\">Un-Ban Officer</a>\n        <% } %>\n      </div>\n    </div>\n  <% } %>\n</td>"),
    events: {
      "change .user_role_select": "update",
      "click .ban-button": "ban",
      "click .unban-button": "unban"
    },
    initialize: function() {
      this.model.bind("change", this.render, this);
      return this.model.bind("destroy", this.remove, this);
    },
    render: function() {
      this.$el.html(this.template(_.extend(this.model.toJSON(), {
        isSuperAdmin: App.options.isSuperAdmin
      })));
      return this;
    },
    ban: function() {
      if (confirm('Are you sure you want to ban this officer? This could have unintended consequences if they were the only officer on a project.')) {
        return this.model.save({
          command: "ban"
        });
      }
    },
    unban: function() {
      return this.model.save({
        command: "unban"
      });
    },
    update: function() {
      return this.model.save({
        role: this.$el.find(".user_role_select").val()
      });
    },
    clear: function() {
      return this.model.clear();
    }
  });
  AppView = Backbone.View.extend({
    initialize: function() {
      Officers.bind('reset', this.reset, this);
      return Officers.bind('all', this.render, this);
    },
    reset: function() {
      $("#officers-tbody").html('');
      return this.addAll();
    },
    render: function() {},
    addOne: function(officer) {
      var html, view;
      view = new OfficerView({
        model: officer
      });
      html = view.render().el;
      return $("#officers-tbody").append(html);
    },
    addAll: function() {
      return Officers.each(this.addOne);
    }
  });
  App = {};
  Officers = {};
  return Rfpez.Backbone.AdminOfficers = function(initialModels) {
    var isSuperAdmin;
    isSuperAdmin = $("body").hasClass('super-admin');
    Officers = new OfficerList;
    App = new AppView({
      collection: Officers,
      isSuperAdmin: isSuperAdmin
    });
    Officers.reset(initialModels);
    return App;
  };
})();

(function() {
  var App, AppView, Project, ProjectList, ProjectView, Projects;
  Project = Backbone.Model.extend({
    validate: function(attrs) {},
    defaults: function() {},
    clear: function() {
      return this.destroy();
    }
  });
  ProjectList = Backbone.Collection.extend({
    model: Project,
    url: "/projects"
  });
  ProjectView = Backbone.View.extend({
    tagName: "tr",
    template: _.template("<td><%- id %></td>\n<td><%- title %></td>\n<td><%- fork_count %></td>\n<td>\n  <select class=\"recommended-select\">\n    <option value=\"1\" <% if (recommended == 1){ %>selected<% } %>>Yes</option>\n    <option value=\"0\" <% if (recommended == 0){ %>selected<% } %>>No</option>\n  </select>\n</td>\n<td>\n  <select class=\"public-select\">\n    <option value=\"1\" <% if (public == 1){ %>selected<% } %>>Yes</option>\n    <option value=\"0\" <% if (public == 0){ %>selected<% } %>>No</option>\n  </select>\n</td>\n<td><%- project_type.name %></td>\n<td>\n  <% if (delisted == 1){ %>\n    <a class=\"btn relist-button btn-mini\">Relist</a>\n  <% } else { %>\n    <a class=\"btn btn-danger delist-button btn-mini\">Delist</a>\n  <% } %>\n</td>"),
    events: {
      "change .recommended-select": "update",
      "change .public-select": "update",
      "click .delist-button": "delist",
      "click .relist-button": "relist"
    },
    delist: function() {
      if (confirm('Are you sure you want to delist this project?')) {
        return this.model.save({
          command: "delist"
        });
      }
    },
    relist: function() {
      return this.model.save({
        command: "relist"
      });
    },
    update: function() {
      return this.model.save({
        recommended: this.$el.find(".recommended-select").val(),
        "public": this.$el.find(".public-select").val()
      });
    },
    initialize: function() {
      this.model.bind("change", this.render, this);
      return this.model.bind("destroy", this.remove, this);
    },
    render: function() {
      this.$el.html(this.template(this.model.toJSON()));
      return this;
    },
    clear: function() {
      return this.model.clear();
    }
  });
  AppView = Backbone.View.extend({
    initialize: function() {
      Projects.bind('reset', this.reset, this);
      return Projects.bind('all', this.render, this);
    },
    reset: function() {
      $("#projects-tbody").html('');
      return this.addAll();
    },
    render: function() {},
    addOne: function(project) {
      var html, view;
      view = new ProjectView({
        model: project
      });
      html = view.render().el;
      return $("#projects-tbody").append(html);
    },
    addAll: function() {
      return Projects.each(this.addOne);
    }
  });
  App = {};
  Projects = {};
  return Rfpez.Backbone.AdminProjects = function(initialModels) {
    Projects = new ProjectList;
    App = new AppView({
      collection: Projects
    });
    Projects.reset(initialModels);
    return App;
  };
})();
