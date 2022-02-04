describe('Editor.', () => {
  beforeEach(() => {
    cy.switchUser('admin');
  });

  it('should open the editor.', () => {
    cy.visitAdmin();
    cy.editPost(1);
    cy.url().should('include', '?post=1&action=edit');
  });

  it('should edit a post.', () => {
    cy.visitAdmin();
    cy.editPost(1);
    cy.get('p.wp-block').type(' A smile betters suits a hero...');
    cy.get('.editor-post-publish-button').click();
  });

  it('should select post content.', () => {
    cy.visitAdmin();
    cy.editPost(1);
    cy.get('p.wp-block')
      .setSelection(' A smile betters suits a hero...')
      .type('{backspace}[Redacted]');
    cy.get('.editor-post-publish-button').click();
  });
});
