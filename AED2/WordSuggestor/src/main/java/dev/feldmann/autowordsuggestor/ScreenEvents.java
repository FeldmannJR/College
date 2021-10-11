package dev.feldmann.autowordsuggestor;

import javax.swing.event.CaretEvent;
import javax.swing.event.CaretListener;
import javax.swing.event.ListSelectionEvent;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.KeyEvent;
import java.util.*;

import static dev.feldmann.autowordsuggestor.MainScreen.Suggestion;

import java.util.List;
import java.util.stream.Collectors;

public class ScreenEvents {

    MainScreen screen;
    Tri tri;
    long updateSuggest = 0;


    public ScreenEvents(MainScreen screen, Tri tri) {
        this.screen = screen;
        this.tri = tri;
    }


    public void clickSuggestedSword(ListSelectionEvent e) {
        if (updateSuggest > System.currentTimeMillis()) {
            return;
        }
        int index = e.getLastIndex();
        screen.fieldText.grabFocus();
        if (!e.getValueIsAdjusting()) {
            autocomplete(index);
        }
    }

    public void showAutoComplete(String word) {
        Random rnd = new Random();
        List<String> suggestion = tri.suggest(word, 6);

        setSuggestions(
                suggestion.stream()
                        .map(suffix -> new Suggestion(word, suffix))
                        .collect(Collectors.toList()).toArray(new MainScreen.Suggestion[suggestion.size()]));

    }

    public void setSuggestions(Suggestion[] vetor) {
        final MainScreen.Suggestion[] strings = vetor;
        java.awt.EventQueue.invokeLater(new Runnable() {
            @Override
            public void run() {
                screen.listSuggestions.setModel(new javax.swing.AbstractListModel<Suggestion>() {
                    public int getSize() {
                        return strings.length;
                    }

                    public Suggestion getElementAt(int i) {
                        return strings[i];
                    }
                });
            }
        });

    }


    public void caretUpdate(CaretEvent e) {


        int index = e.getDot();
        index--;

        String word = index < 0 ? "" : getWordAt(index);
        if (word != null) {
            showAutoComplete(word);
        }

    }

    public String getWordAt(int index) {
        String text = screen.fieldText.getText();
        if (index < 0) {
            return "";
        }
        if (index + 1 >= text.length() || text.charAt(index + 1) == ' ') {
            int start = index;
            while (start > 0) {
                if (text.charAt(start) == ' ') {
                    start++;
                    break;
                }
                start--;
            }
            String word = text.substring(start, index + 1).trim();
            return word;
        }
        return null;
    }

    public void keyPressed(KeyEvent ev) {
        if (ev.getKeyCode() == KeyEvent.VK_ENTER) {
            send();
        }
        if (ev.getKeyCode() == KeyEvent.VK_TAB) {
            ev.consume();
            autocomplete(0);
        }
    }

    private void autocomplete(int index) {

        Suggestion sg = screen.listSuggestions.getModel().getElementAt(index);
        java.awt.EventQueue.invokeLater(() -> {
            screen.listSuggestions.clearSelection();
            screen.fieldText.setText(screen.fieldText.getText() + sg.suffix + " ");
            tri.addWeight(sg.toString());
        });
        updateSuggest = System.currentTimeMillis() + 300;

    }

    public void keyTyped(KeyEvent ev) {

        if (ev.getKeyChar() == ' ') {
            int index = screen.fieldText.getCaret().getDot();
            index--;
            String word = getWordAt(index);
            if (word != null) {
                if (tri.hasWord(word)) {
                    this.tri.addWeight(word);
                }
            }
        }
    }

    public void send() {
        String text = this.screen.fieldText.getText();
        if (text.trim().isEmpty()) return;
        this.screen.fieldText.setText("");
        this.screen.fieldText.grabFocus();
        this.screen.textAreaText.append(text + '\n');
    }

    public void clickSend(ActionEvent ev) {
        send();
    }

    public void resetWeight() {
        tri.resetWeights();
    }
}
