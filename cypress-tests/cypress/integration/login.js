describe('Login.', () => {
  beforeEach(() => {
    cy.switchUser('admin');
  });

  it('should move to the login page.', () => {
    cy.visitAdmin();
    cy.url().should('include', '/wp-admin/index.php');
  });

  it('should switch user to editor.', () => {
    cy.visitAdmin();
    cy.get('.display-name').contains('admin');
    cy.switchUser('editor');
    cy.visitAdmin();
    cy.get('.display-name').should('not.contain', 'admin');
  });

  it('should switch user via login.', () => {
    const urlList = [];
    cy.on('url:changed', (newUrl) => {
      urlList.push(newUrl.replace('http://localhost:8181/', ''));
    });
    cy.visitAdmin();
    cy.get('.display-name').contains('admin');
    cy.switchUser('editor', 'password');
    cy.get('.display-name')
      .should('not.contain', 'admin')
      .then(() => {
        expect(urlList).to.deep.eq([
          'wp-admin/index.php',
          'wp-login.php?loggedout=true',
          'wp-admin/',
        ]);
      });
    cy.log('URLs visited:', urlList);
  });
});
